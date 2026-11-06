<?php

namespace App\Support;

use App\Models\User;

/**
 * Protect the platform from losing its last administrator.
 *
 * Staff members may only change staff modes, but a defensive guard still
 * checks the resulting state so no code path can leave the platform without an
 * administrator.
 */
final class AdminRoleGuard
{
    /**
     * @param  list<string>  $roles  The roles the user would end up holding.
     */
    public static function wouldRemoveLastAdmin(User $user, array $roles): bool
    {
        $losesAdmin = $user->hasRole(UserMode::ADMIN)
            && ! in_array(UserMode::ADMIN, $roles, true);

        return $losesAdmin && User::role(UserMode::ADMIN)->count() <= 1;
    }
}
