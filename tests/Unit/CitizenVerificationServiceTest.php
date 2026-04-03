<?php

namespace Tests\Unit;

use App\Models\Kyc;
use App\Models\User;
use App\Services\CitizenVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CitizenVerificationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_low_risk_profile_gets_auto_verified(): void
    {
        Storage::fake('public');

        $front = UploadedFile::fake()->image('front.jpg', 1400, 900)->store('uploads', 'public');
        $back = UploadedFile::fake()->image('back.jpg', 1300, 900)->store('uploads', 'public');

        $user = User::factory()->create([
            'phone' => '9812345678',
        ]);

        $kyc = Kyc::create([
            'user_id' => $user->id,
            'nagrita_number' => '54-01-75-00023',
            'date_of_birth' => '1990-01-01',
            'district' => 'Kathmandu',
            'ward_no' => 5,
            'nagrita_front' => $front,
            'nagrita_back' => $back,
        ]);

        $result = app(CitizenVerificationService::class)->evaluate($user, $kyc);

        $this->assertSame('verified', $result['status']);
        $this->assertGreaterThanOrEqual(85, $result['score']);
        $this->assertTrue($result['activated']);
    }

    public function test_underage_profile_is_rejected(): void
    {
        Storage::fake('public');

        $front = UploadedFile::fake()->image('front.jpg', 1400, 900)->store('uploads', 'public');
        $back = UploadedFile::fake()->image('back.jpg', 1300, 900)->store('uploads', 'public');

        $user = User::factory()->create([
            'phone' => '9812345678',
        ]);

        $kyc = Kyc::create([
            'user_id' => $user->id,
            'nagrita_number' => '54-01-75-00024',
            'date_of_birth' => now()->subYears(16)->toDateString(),
            'district' => 'Kathmandu',
            'ward_no' => 5,
            'nagrita_front' => $front,
            'nagrita_back' => $back,
        ]);

        $result = app(CitizenVerificationService::class)->evaluate($user, $kyc);

        $this->assertSame('rejected', $result['status']);
        $this->assertFalse($result['activated']);
        $this->assertNotEmpty($result['rejection_reason']);
    }

    public function test_duplicate_citizenship_number_is_rejected(): void
    {
        Storage::fake('public');

        $existingUser = User::factory()->create([
            'phone' => '9811111111',
        ]);

        Kyc::create([
            'user_id' => $existingUser->id,
            'nagrita_number' => '54-01-75-99999',
            'nagrita_number_normalized' => '54017599999',
            'verification_status' => 'verified',
            'verification_score' => 95,
        ]);

        $front = UploadedFile::fake()->image('front2.jpg', 1400, 900)->store('uploads', 'public');
        $back = UploadedFile::fake()->image('back2.jpg', 1300, 900)->store('uploads', 'public');

        $user = User::factory()->create([
            'phone' => '9812345678',
        ]);

        $kyc = Kyc::create([
            'user_id' => $user->id,
            'nagrita_number' => '54017599999',
            'date_of_birth' => '1990-01-01',
            'district' => 'Kathmandu',
            'ward_no' => 8,
            'nagrita_front' => $front,
            'nagrita_back' => $back,
        ]);

        $result = app(CitizenVerificationService::class)->evaluate($user, $kyc);

        $this->assertSame('rejected', $result['status']);
        $this->assertContains('DUPLICATE_CITIZENSHIP_NUMBER', array_column($result['flags'], 'code'));
    }
}
