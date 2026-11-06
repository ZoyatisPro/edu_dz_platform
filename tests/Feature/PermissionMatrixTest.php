<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\RolePermissions;
use App\Support\UserMode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionMatrixTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_only_reach_public_pages()
    {
        $this->get(route('home'))->assertOk();
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('space'))->assertRedirect(route('login'));
        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
    }

    public function test_each_mode_holds_the_expected_permissions()
    {
        $this->seedRoles();

        $expected = [
            UserMode::STUDENT => [],
            UserMode::PARENT => [],
            UserMode::TEACHER => [RolePermissions::MANAGE_GROUPS, RolePermissions::MANAGE_CONTENT],
            UserMode::ASSISTANT => RolePermissions::all(),
            UserMode::ADMIN => RolePermissions::all(),
        ];

        foreach ($expected as $mode => $permissions) {
            $user = User::factory()->create();
            $user->assignRole($mode);

            foreach (RolePermissions::all() as $permission) {
                $this->assertSame(
                    in_array($permission, $permissions, true),
                    $user->hasPermissionTo($permission),
                    "Mode [{$mode}] permission [{$permission}] mismatch.",
                );
            }
        }
    }

    public function test_the_administration_policy_matches_the_permission_matrix()
    {
        $this->seedRoles();

        $expectations = [
            UserMode::STUDENT => false,
            UserMode::PARENT => false,
            UserMode::TEACHER => false,
            UserMode::ASSISTANT => true,
            UserMode::ADMIN => true,
        ];

        foreach ($expectations as $mode => $allowed) {
            $user = User::factory()->create();
            $user->assignRole($mode);

            $this->assertSame($allowed, $user->can('viewAny', User::class));
            $this->assertSame($allowed, $user->can('manageRoles', User::factory()->create()));
        }
    }

    public function test_users_without_a_mode_have_no_platform_permission()
    {
        $this->seedRoles();

        $user = User::factory()->create();

        foreach (RolePermissions::all() as $permission) {
            $this->assertFalse($user->hasPermissionTo($permission));
        }

        $this->assertFalse($user->can('viewAny', User::class));
        $this->assertFalse($user->can('manageRoles', User::factory()->create()));
    }

    public function test_roles_are_seeded_exactly_once()
    {
        $this->seedRoles();
        $this->seedRoles();

        $this->assertDatabaseCount('roles', count(UserMode::ALL));
        $this->assertDatabaseCount('permissions', count(RolePermissions::all()));
    }
}
