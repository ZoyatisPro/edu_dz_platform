<?php

namespace App\Policies;

use App\Models\User;
use App\Support\RolePermissions;

final class UserPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->hasPermissionTo(RolePermissions::VIEW_USERS);
    }

    /**
     * The actor may open the user in the administration area.
     */
    public function view(User $actor, User $target): bool
    {
        return $actor->hasPermissionTo(RolePermissions::VIEW_USERS);
    }

    /**
     * Only staff members with the role permission may change modes.
     */
    public function manageRoles(User $actor, User $target): bool
    {
        return $actor->hasPermissionTo(RolePermissions::MANAGE_ROLES);
    }
}
