<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Models\User;
use App\Services\CitizenVerificationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, CitizenVerificationService $citizenVerificationService): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'nagrita_number' => ['required', 'string', 'max:255', 'regex:/^[0-9०-९\\-\\/\\s]+$/u'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'district' => ['required', 'string', 'max:100'],
            'ward_no' => ['required', 'integer', 'between:1,35'],
            'nagrita_front' => ['required', 'image', 'max:1024'],
            'nagrita_back' => ['required', 'image', 'max:1024'],
        ]);

        try {
            [$user, $verificationResult] = DB::transaction(function () use ($request, $citizenVerificationService) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                ]);

                $kyc = Kyc::create([
                    'user_id' => $user->id,
                    'nagrita_number' => $request->nagrita_number,
                    'date_of_birth' => $request->date_of_birth,
                    'district' => $request->district,
                    'ward_no' => $request->ward_no,
                    'nagrita_front' => $this->uploadNagritaFile($request->file('nagrita_front')),
                    'nagrita_back' => $this->uploadNagritaFile($request->file('nagrita_back')),
                ]);

                $verificationResult = $citizenVerificationService->evaluate($user, $kyc);

                $kyc->update([
                    'nagrita_number_normalized' => $verificationResult['nagrita_number_normalized'],
                    'nagrita_front_hash' => $verificationResult['nagrita_front_hash'],
                    'nagrita_back_hash' => $verificationResult['nagrita_back_hash'],
                    'verification_status' => $verificationResult['status'],
                    'verification_score' => $verificationResult['score'],
                    'verification_flags' => $verificationResult['flags'],
                    'verified_at' => $verificationResult['verified_at'],
                    'last_verified_at' => now(),
                    'rejection_reason' => $verificationResult['rejection_reason'],
                ]);

                $user->update([
                    'is_active' => $verificationResult['activated'],
                ]);

                return [$user, $verificationResult];
            });

            event(new Registered($user));

            if ($verificationResult['status'] === 'verified') {
                return redirect()->route('login')->with('status', 'KYC auto-verified. You can login and vote after email verification.');
            }

            if ($verificationResult['status'] === 'manual_review') {
                return redirect()->route('login')->with('error', 'Your KYC needs manual review. Admin will verify your details shortly.');
            }

            return redirect()->route('login')->with('error', 'Your KYC was rejected automatically. Please contact support or submit valid documents.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function uploadNagritaFile($file)
    {
        $fileName = time().'_'.$file->getClientOriginalName();
        $filePath = $file->storeAs('uploads', $fileName, 'public');
        return $filePath;
    }
}
