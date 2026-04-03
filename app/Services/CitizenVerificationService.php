<?php

namespace App\Services;

use App\Models\Kyc;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CitizenVerificationService
{
    private const STATUS_VERIFIED = 'verified';
    private const STATUS_MANUAL_REVIEW = 'manual_review';
    private const STATUS_REJECTED = 'rejected';

    /**
     * @return array{
     *     status: string,
     *     score: int,
     *     flags: array<int, array{code: string, severity: string, message: string}>,
     *     nagrita_number_normalized: ?string,
     *     nagrita_front_hash: ?string,
     *     nagrita_back_hash: ?string,
     *     verified_at: ?string,
     *     rejection_reason: ?string,
     *     activated: bool
     * }
     */
    public function evaluate(User $user, Kyc $kyc): array
    {
        $score = 100;
        $flags = [];
        $hardFail = false;

        $normalizedNagrita = $this->normalizeCitizenshipNumber($kyc->nagrita_number);

        if ($normalizedNagrita === null || strlen($normalizedNagrita) < 8 || strlen($normalizedNagrita) > 20) {
            $score -= 30;
            $flags[] = $this->flag('INVALID_CITIZENSHIP_FORMAT', 'high', 'Citizenship number format is invalid.');
        }

        if ($normalizedNagrita !== null && $this->hasRepeatedOrSequentialPattern($normalizedNagrita)) {
            $score -= 20;
            $flags[] = $this->flag('SUSPICIOUS_CITIZENSHIP_PATTERN', 'medium', 'Citizenship number contains a suspicious numeric pattern.');
        }

        if ($normalizedNagrita !== null && $this->isDuplicateCitizenshipNumber($kyc, $normalizedNagrita)) {
            $hardFail = true;
            $flags[] = $this->flag('DUPLICATE_CITIZENSHIP_NUMBER', 'critical', 'The citizenship number is already registered by another user.');
        }

        if (! $this->isValidNepalMobile($user->phone)) {
            $score -= 15;
            $flags[] = $this->flag('INVALID_NEPAL_MOBILE', 'medium', 'Phone number does not match Nepal mobile format.');
        }

        if (! $this->isValidDistrict($kyc->district)) {
            $score -= 15;
            $flags[] = $this->flag('INVALID_DISTRICT', 'medium', 'District is not recognized as a Nepal district.');
        }

        if (! $this->isValidWard($kyc->ward_no)) {
            $score -= 10;
            $flags[] = $this->flag('INVALID_WARD', 'medium', 'Ward number must be between 1 and 35.');
        }

        if (! $this->isEligibleAge($kyc->date_of_birth)) {
            $hardFail = true;
            $flags[] = $this->flag('UNDERAGE_CITIZEN', 'critical', 'Citizen must be at least 18 years old to vote in Nepal.');
        }

        $frontScan = $this->scanImage($kyc->nagrita_front);
        $backScan = $this->scanImage($kyc->nagrita_back);

        if (! $frontScan['ok'] || ! $backScan['ok']) {
            $hardFail = true;
            $flags[] = $this->flag('MISSING_OR_CORRUPT_DOCUMENT', 'critical', 'Citizenship document image is missing or unreadable.');
        }

        if ($frontScan['ok'] && $backScan['ok']) {
            if ($frontScan['hash'] === $backScan['hash']) {
                $hardFail = true;
                $flags[] = $this->flag('SAME_FRONT_BACK_DOCUMENT', 'critical', 'Front and back citizenship images are identical.');
            }

            if ($frontScan['low_quality']) {
                $score -= 20;
                $flags[] = $this->flag('LOW_QUALITY_FRONT_IMAGE', 'medium', 'Front image quality is too low for reliable verification.');
            }

            if ($backScan['low_quality']) {
                $score -= 20;
                $flags[] = $this->flag('LOW_QUALITY_BACK_IMAGE', 'medium', 'Back image quality is too low for reliable verification.');
            }

            if ($this->isDocumentHashReused($kyc, $frontScan['hash'], $backScan['hash'])) {
                $score -= 40;
                $flags[] = $this->flag('DOCUMENT_HASH_REUSED', 'high', 'Citizenship document image has been reused in another account.');
            }
        }

        $score = max(0, min(100, $score));

        $status = $this->resolveStatus($score, $hardFail);
        $verifiedAt = $status === self::STATUS_VERIFIED ? now()->toDateTimeString() : null;

        return [
            'status' => $status,
            'score' => $score,
            'flags' => $flags,
            'nagrita_number_normalized' => $normalizedNagrita,
            'nagrita_front_hash' => $frontScan['hash'],
            'nagrita_back_hash' => $backScan['hash'],
            'verified_at' => $verifiedAt,
            'rejection_reason' => $this->buildRejectionReason($status, $flags),
            'activated' => $status === self::STATUS_VERIFIED,
        ];
    }

    private function resolveStatus(int $score, bool $hardFail): string
    {
        if ($hardFail || $score < 60) {
            return self::STATUS_REJECTED;
        }

        if ($score < 85) {
            return self::STATUS_MANUAL_REVIEW;
        }

        return self::STATUS_VERIFIED;
    }

    /**
     * @return array{ok: bool, hash: ?string, low_quality: bool}
     */
    private function scanImage(?string $path): array
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return ['ok' => false, 'hash' => null, 'low_quality' => false];
        }

        $absolutePath = Storage::disk('public')->path($path);
        $hash = @hash_file('sha256', $absolutePath) ?: null;
        $dimensions = @getimagesize($absolutePath);

        if ($hash === null || $dimensions === false) {
            return ['ok' => false, 'hash' => $hash, 'low_quality' => false];
        }

        $width = $dimensions[0] ?? 0;
        $height = $dimensions[1] ?? 0;

        // Proxy quality rule: very small dimensions usually mean unreadable KYC documents.
        $lowQuality = $width < 900 || $height < 550;

        return ['ok' => true, 'hash' => $hash, 'low_quality' => $lowQuality];
    }

    private function normalizeCitizenshipNumber(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $normalizedDigits = $this->normalizeNepaliDigits($value);
        $normalized = preg_replace('/\D+/', '', $normalizedDigits);

        return $normalized !== '' ? $normalized : null;
    }

    private function normalizeNepaliDigits(string $value): string
    {
        return strtr($value, [
            '०' => '0',
            '१' => '1',
            '२' => '2',
            '३' => '3',
            '४' => '4',
            '५' => '5',
            '६' => '6',
            '७' => '7',
            '८' => '8',
            '९' => '9',
        ]);
    }

    private function hasRepeatedOrSequentialPattern(string $value): bool
    {
        if (preg_match('/^(\d)\1{5,}$/', $value) === 1) {
            return true;
        }

        $length = strlen($value);
        $ascending = true;
        $descending = true;

        for ($i = 1; $i < $length; $i++) {
            $prev = (int) $value[$i - 1];
            $curr = (int) $value[$i];

            if ($curr !== ($prev + 1) % 10) {
                $ascending = false;
            }

            if ($curr !== ($prev + 9) % 10) {
                $descending = false;
            }
        }

        return $ascending || $descending;
    }

    private function isEligibleAge(mixed $dateOfBirth): bool
    {
        if (! $dateOfBirth) {
            return false;
        }

        try {
            if ($dateOfBirth instanceof \DateTimeInterface) {
                return Carbon::instance($dateOfBirth)->age >= 18;
            }

            return Carbon::parse((string) $dateOfBirth)->age >= 18;
        } catch (\Throwable) {
            return false;
        }
    }

    private function isValidNepalMobile(?string $phone): bool
    {
        if ($phone === null || trim($phone) === '') {
            return false;
        }

        $normalized = preg_replace('/\D+/', '', $this->normalizeNepaliDigits($phone));

        return preg_match('/^9[678]\d{8}$/', $normalized) === 1;
    }

    private function isValidWard(mixed $ward): bool
    {
        if (! is_numeric($ward)) {
            return false;
        }

        $wardNo = (int) $ward;

        return $wardNo >= 1 && $wardNo <= 35;
    }

    private function isValidDistrict(?string $district): bool
    {
        if ($district === null || trim($district) === '') {
            return false;
        }

        return in_array(mb_strtolower(trim($district)), $this->nepalDistricts(), true);
    }

    /**
     * @return array<int, string>
     */
    private function nepalDistricts(): array
    {
        return [
            'achham', 'arghakhanchi', 'baglung', 'baitadi', 'bajhang', 'bajura', 'banke',
            'bara', 'bardiya', 'bhaktapur', 'bhojpur', 'chitwan', 'dadeldhura', 'dailekh',
            'dang', 'darchula', 'dhading', 'dhankuta', 'dhanusha', 'dholkha', 'dolpa',
            'doti', 'eastern rukum', 'gorkha', 'gulmi', 'humla', 'ilam', 'jajarkot',
            'jhapa', 'jumla', 'kailali', 'kalikot', 'kanchanpur', 'kapilvastu', 'kaski',
            'kathmandu', 'kavrepalanchok', 'khotang', 'lalitpur', 'lamjung', 'mahottari',
            'makwanpur', 'manang', 'morang', 'mugu', 'mustang', 'myagdi', 'nawalpur',
            'nuwakot', 'okhaldhunga', 'palpa', 'panchthar', 'parasi', 'parbat', 'parsa',
            'pyuthan', 'ramechhap', 'rasuwa', 'rautahat', 'rolpa', 'rupandehi', 'salyan',
            'sankhuwasabha', 'saptari', 'sarlahi', 'sindhuli', 'sindhupalchok', 'siraha',
            'solukhumbu', 'sunsari', 'surkhet', 'syangja', 'tanahun', 'taplejung',
            'tehrathum', 'udadyapur', 'udayapur', 'western rukum',
        ];
    }

    private function isDuplicateCitizenshipNumber(Kyc $kyc, string $normalizedNumber): bool
    {
        return Kyc::query()
            ->where('id', '!=', $kyc->id)
            ->where('nagrita_number_normalized', $normalizedNumber)
            ->exists();
    }

    private function isDocumentHashReused(Kyc $kyc, ?string $frontHash, ?string $backHash): bool
    {
        if (! $frontHash && ! $backHash) {
            return false;
        }

        return Kyc::query()
            ->where('id', '!=', $kyc->id)
            ->where(function ($query) use ($frontHash, $backHash) {
                if ($frontHash) {
                    $query->orWhere('nagrita_front_hash', $frontHash)
                        ->orWhere('nagrita_back_hash', $frontHash);
                }

                if ($backHash) {
                    $query->orWhere('nagrita_front_hash', $backHash)
                        ->orWhere('nagrita_back_hash', $backHash);
                }
            })
            ->exists();
    }

    /**
     * @return array{code: string, severity: string, message: string}
     */
    private function flag(string $code, string $severity, string $message): array
    {
        return [
            'code' => $code,
            'severity' => $severity,
            'message' => $message,
        ];
    }

    /**
     * @param  array<int, array{code: string, severity: string, message: string}>  $flags
     */
    private function buildRejectionReason(string $status, array $flags): ?string
    {
        if ($status !== self::STATUS_REJECTED) {
            return null;
        }

        if ($flags === []) {
            return 'Verification rejected due to policy checks.';
        }

        return collect($flags)->pluck('message')->take(3)->implode(' ');
    }
}
