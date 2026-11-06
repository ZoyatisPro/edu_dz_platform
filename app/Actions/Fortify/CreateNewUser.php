<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use App\Services\Turnstile;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    private const MAX_ATTEMPTS_PER_MINUTE = 5;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $this->ensureRegistrationIsNotThrottled();

        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        if (! app(Turnstile::class)->verify(
            $input['cf-turnstile-response'] ?? null,
            request()->ip(),
        )) {
            throw ValidationException::withMessages([
                'cf-turnstile-response' => __('validation.turnstile'),
            ]);
        }

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
            'locale' => app()->getLocale(),
            'password' => $input['password'],
        ]);
    }

    /**
     * Limit registrations per IP address to slow down automated sign-ups.
     */
    private function ensureRegistrationIsNotThrottled(): void
    {
        $key = 'register:'.(request()->ip() ?? 'unknown');

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS_PER_MINUTE)) {
            throw ValidationException::withMessages([
                'email' => __('auth.throttle', [
                    'seconds' => RateLimiter::availableIn($key),
                ]),
            ]);
        }

        RateLimiter::hit($key, 60);
    }
}
