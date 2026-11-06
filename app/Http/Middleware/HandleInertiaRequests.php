<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'displayName' => config('app.display_name'),
            'locale' => $locale,
            'direction' => in_array($locale, config('app.rtl_locales', []), true) ? 'rtl' : 'ltr',
            'supportedLocales' => array_map(
                fn (string $code): array => [
                    'code' => $code,
                    'label' => trans("app.language.{$code}"),
                ],
                $this->supportedLocales(),
            ),
            'translations' => trans('app'),
            'auth' => [
                'user' => $request->user(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }

    /**
     * Locales accepted by the application, guaranteed to be a list of strings.
     *
     * @return list<string>
     */
    private function supportedLocales(): array
    {
        $locales = config('app.supported_locales', ['fr']);

        if (! is_array($locales)) {
            return ['fr'];
        }

        return array_values(array_filter($locales, is_string(...)));
    }
}
