<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        $allowedPlatformEmails = collect(explode(',', (string) env('PLATFORM_ADMIN_EMAILS', '')))
            ->map(fn (string $email) => trim($email))
            ->filter()
            ->values();

        $isPlatformAdmin = $user
            && ($user->isPlatformAdmin() || $allowedPlatformEmails->contains(fn (string $email) => strcasecmp($email, (string) $user->email) === 0));

        $redirectTo = $isPlatformAdmin ? route('platform-admin.dashboard') : config('fortify.home');

        return $request->wantsJson()
            ? new JsonResponse(['two_factor' => false])
            : redirect()->intended($redirectTo);
    }
}
