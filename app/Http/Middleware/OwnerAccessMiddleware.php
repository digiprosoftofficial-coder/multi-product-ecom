<?php

namespace App\Http\Middleware;

use App\Support\OwnerAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerAccessMiddleware
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $allowed = match ($feature) {
            'site_setting' => $user->canAccessSiteSetting(),
            'settings' => $user->canAccessSettingsPage(),
            'seo' => $user->canAccessSeo(),
            'payment' => $user->canAccessPayment(),
            default => OwnerAccess::enabled($feature) && $user->hasRole('store_owner'),
        };

        if (! $allowed) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
