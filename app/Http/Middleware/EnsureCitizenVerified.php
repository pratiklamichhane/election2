<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCitizenVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $kyc = $user->kyc;

        if (! $kyc) {
            return redirect()->route('dashboard')->with('error', 'KYC profile is missing. Please contact support.');
        }

        if ($kyc->verification_status === 'rejected') {
            return redirect()->route('dashboard')->with('error', 'Your KYC is rejected. You are not eligible to vote.');
        }

        if ($kyc->verification_status === 'manual_review') {
            return redirect()->route('dashboard')->with('error', 'Your KYC is under manual review. Voting is enabled after approval.');
        }

        if (! $user->is_active || $kyc->verification_status !== 'verified') {
            return redirect()->route('dashboard')->with('error', 'Your account is pending verification.');
        }

        return $next($request);
    }
}
