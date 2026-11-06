<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Cloudflare Turnstile verification.
 *
 * The feature is dormant until both keys are configured, so local development
 * and tests never call the Cloudflare API.
 */
final class Turnstile
{
    public function enabled(): bool
    {
        return $this->secret() !== '';
    }

    public function verify(?string $token, ?string $ip = null): bool
    {
        if (! $this->enabled()) {
            return true;
        }

        if ($token === null || $token === '') {
            return false;
        }

        $response = Http::asForm()
            ->timeout(10)
            ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $this->secret(),
                'response' => $token,
                'remoteip' => $ip,
            ]);

        return $response->successful() && $response->json('success') === true;
    }

    private function secret(): string
    {
        $secret = config('services.turnstile.secret_key');

        return is_string($secret) ? $secret : '';
    }
}
