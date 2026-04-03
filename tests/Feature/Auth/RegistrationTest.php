<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register and get auto-verified when risk is low', function () {
    Storage::fake('public');

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '9812345678',
        'nagrita_number' => '54-01-75-00023',
        'date_of_birth' => '1995-05-10',
        'district' => 'Kathmandu',
        'ward_no' => 7,
        'nagrita_front' => UploadedFile::fake()->image('front.jpg', 1400, 900),
        'nagrita_back' => UploadedFile::fake()->image('back.jpg', 1300, 900),
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('login'));

    $user = \App\Models\User::where('email', 'test@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->is_active)->toBeTrue();
    expect($user->kyc)->not->toBeNull();
    expect($user->kyc->verification_status)->toBe('verified');
    expect($user->kyc->verification_score)->toBeGreaterThanOrEqual(85);
});
