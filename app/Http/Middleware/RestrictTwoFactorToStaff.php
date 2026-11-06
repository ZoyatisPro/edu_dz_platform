<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keep two-factor authentication management for the platform staff.
 *
 * The settings screen already hides the feature for regular users, but the
 * Fortify endpoints must be protected too so nobody can enable it by calling
 * the API directly.
 */
class RestrictTwoFactorToStaff
{
    /**
     * Fortify routes that manage the authenticated user's two-factor state.
     *
     * The login challenge route is intentionally excluded: it must stay
     * reachable for staff who already enabled the feature.
     *
     * @var list<string>
     */
    private const ROUTES = [
        'two-factor.enable',
        'two-factor.disable',
        'two-factor.confirm',
        'two-factor.qr-code',
        'two-factor.secret-key',
        'two-factor.recovery-codes',
        'two-factor.regenerate-recovery-codes',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $name = $request->route()?->getName();

        if (in_array($name, self::ROUTES, true) && ! $request->user()?->isStaff()) {
            abort(403);
        }

        return $next($request);
    }
}
