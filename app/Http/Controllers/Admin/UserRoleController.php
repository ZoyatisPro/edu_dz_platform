<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRolesRequest;
use App\Models\User;
use App\Support\AdminRoleGuard;
use App\Support\UserMode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class UserRoleController extends Controller
{
    /**
     * Replace the modes held by a user.
     */
    public function update(UserRolesRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('manageRoles', $user);

        $actor = $request->user();

        if ($actor->is($user)) {
            abort(403, __('app.admin.users.cannot_edit_self'));
        }

        /** @var list<string> $roles */
        $roles = $request->validated('roles');

        $current = $user->getRoleNames()->all();

        if (! $actor->hasRole(UserMode::ADMIN)) {
            $changed = [...array_diff($roles, $current), ...array_diff($current, $roles)];

            if (array_intersect($changed, UserMode::STAFF) !== []) {
                abort(403, __('app.admin.users.staff_roles_locked'));
            }
        }

        if (AdminRoleGuard::wouldRemoveLastAdmin($user, $roles)) {
            throw ValidationException::withMessages([
                'roles' => __('app.admin.users.last_admin'),
            ]);
        }

        $user->syncRoles($roles);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('app.admin.users.modes_updated', ['name' => $user->name]),
        ]);

        return back();
    }
}
