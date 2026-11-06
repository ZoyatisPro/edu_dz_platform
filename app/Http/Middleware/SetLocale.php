<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Apply the locale chosen by the visitor.
     *
     * Resolution order: session, authenticated user preference, cookie,
     * configured default. Only locales listed in
     * `config('app.supported_locales')` are accepted, so a forged value can
     * never reach the translator.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('app.supported_locales', ['fr']);
        $fallback = config('app.locale', 'fr');

        $locale = $request->session()->get('locale')
            ?? data_get($request->user(), 'locale')
            ?? $request->cookie('locale')
            ?? $fallback;

        if (! is_string($locale) || ! in_array($locale, $supported, true)) {
            $locale = $fallback;
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
