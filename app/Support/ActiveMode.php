<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Resolve and remember the mode the user is currently working in.
 *
 * The choice lives in the session: a user holding several modes can switch
 * freely without writing to the database on every click.
 */
final class ActiveMode
{
    public const SESSION_KEY = 'active_mode';

    public static function for(Request $request): ?string
    {
        $user = $request->user();

        if ($user === null) {
            return null;
        }

        $owned = array_values(array_filter(
            $user->getRoleNames()->all(),
            is_string(...),
        ));
        $current = $request->session()->get(self::SESSION_KEY);

        if (is_string($current) && in_array($current, $owned, true)) {
            return $current;
        }

        return UserMode::defaultFor($owned);
    }

    public static function set(Request $request, string $mode): void
    {
        $request->session()->put(self::SESSION_KEY, $mode);
    }
}
