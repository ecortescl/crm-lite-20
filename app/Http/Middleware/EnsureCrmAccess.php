<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCrmAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $allowedPlatformEmails = collect(explode(',', (string) env('PLATFORM_ADMIN_EMAILS', '')))
            ->map(fn (string $email) => trim($email))
            ->filter()
            ->values();

        $isPlatformAdmin = $user->isPlatformAdmin()
            || $allowedPlatformEmails->contains(fn (string $email) => strcasecmp($email, (string) $user->email) === 0);

        if ($isPlatformAdmin) {
            abort(403);
        }

        return $next($request);
    }
}
