<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Persist the visitor's locale and send them back where they came from.
     */
    public function update(Request $request, string $locale): RedirectResponse
    {
        abort_unless(
            in_array($locale, config('app.supported_locales', ['fr']), true),
            404,
        );

        $request->session()->put('locale', $locale);

        $request->user()?->update(['locale' => $locale]);

        return back()->withCookie(
            cookie()->forever('locale', $locale, '/', null, false, false),
        );
    }
}
