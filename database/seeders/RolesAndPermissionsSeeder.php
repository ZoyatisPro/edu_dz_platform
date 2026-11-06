<?php

namespace Database\Seeders;

use App\Support\RolePermissions;
use App\Support\UserMode;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Create the platform modes (roles) and their permissions.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (RolePermissions::all() as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        foreach (UserMode::ALL as $mode) {
            $role = Role::findOrCreate($mode, 'web');
            $role->syncPermissions(RolePermissions::forRole($mode));
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
