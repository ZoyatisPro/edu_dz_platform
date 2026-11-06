<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Support\ActiveMode;
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
        $user = $request->user();

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
                'user' => $user === null ? null : $this->userPayload($user),
            ],
            'activeMode' => ActiveMode::for($request),
            'turnstileSiteKey' => $this->turnstileSiteKey(),
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }

    /**
     * The subset of the authenticated user exposed to the frontend.
     *
     * @return array<string, mixed>
     */
    private function userPayload(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'email_verified_at' => $user->email_verified_at?->toIso8601String(),
            'locale' => $user->locale,
            'roles' => $user->getRoleNames()->all(),
            'active_mode' => ActiveMode::for(request()),
            'is_staff' => $user->isStaff(),
            'teacher_request_status' => $user->teacherRequest?->status->value,
            'two_factor_enabled' => $user->two_factor_confirmed_at !== null,
            'created_at' => $user->created_at?->toIso8601String(),
            'updated_at' => $user->updated_at?->toIso8601String(),
        ];
    }

    /**
     * Turnstile is only rendered when a site key is configured.
     */
    private function turnstileSiteKey(): ?string
    {
        $key = config('services.turnstile.site_key');

        return is_string($key) && $key !== '' ? $key : null;
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
