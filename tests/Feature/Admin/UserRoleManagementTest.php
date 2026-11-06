<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Support\AdminRoleGuard;
use App\Support\UserMode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UserRoleManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page()
    {
        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
    }

    public function test_admins_and_assistants_can_review_the_user_list()
    {
        foreach ([UserMode::ADMIN, UserMode::ASSISTANT] as $staffMode) {
            $actor = $this->userWithRole($staffMode);

            $this->actingAs($actor)
                ->get(route('admin.users.index'))
                ->assertOk()
                ->assertInertia(fn (Assert $page) => $page
                    ->component('admin/users/index')
                    ->has('users.data'),
                );
        }
    }

    public function test_students_teachers_and_parents_cannot_review_the_user_list()
    {
        foreach ([UserMode::STUDENT, UserMode::PARENT, UserMode::TEACHER] as $mode) {
            $actor = $this->userWithRole($mode);

            $this->actingAs($actor)
                ->get(route('admin.users.index'))
                ->assertForbidden();
        }
    }

    public function test_an_admin_can_grant_any_mode()
    {
        $this->seedRoles();

        $actor = User::factory()->create();
        $actor->assignRole(UserMode::ADMIN);

        $target = User::factory()->create();

        $this->actingAs($actor)
            ->from(route('admin.users.index'))
            ->patch(route('admin.users.roles.update', $target), [
                'roles' => [UserMode::STUDENT, UserMode::ASSISTANT],
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHasNoErrors();

        $this->assertEqualsCanonicalizing(
            [UserMode::STUDENT, UserMode::ASSISTANT],
            $target->fresh()->getRoleNames()->all(),
        );
    }

    public function test_an_admin_can_grant_the_admin_mode()
    {
        $this->seedRoles();

        $actor = User::factory()->create();
        $actor->assignRole(UserMode::ADMIN);

        $target = User::factory()->create();

        $this->actingAs($actor)
            ->patch(route('admin.users.roles.update', $target), [
                'roles' => [UserMode::ADMIN],
            ])
            ->assertSessionHasNoErrors();

        $this->assertTrue($target->fresh()->hasRole(UserMode::ADMIN));
    }

    public function test_an_assistant_can_manage_regular_modes()
    {
        $this->seedRoles();

        $actor = User::factory()->create();
        $actor->assignRole(UserMode::ASSISTANT);

        $target = User::factory()->create();
        $target->assignRole(UserMode::STUDENT);

        $this->actingAs($actor)
            ->patch(route('admin.users.roles.update', $target), [
                'roles' => [UserMode::TEACHER, UserMode::PARENT],
            ])
            ->assertSessionHasNoErrors();

        $this->assertEqualsCanonicalizing(
            [UserMode::TEACHER, UserMode::PARENT],
            $target->fresh()->getRoleNames()->all(),
        );
    }

    public function test_an_assistant_cannot_grant_staff_modes()
    {
        $this->seedRoles();

        $actor = User::factory()->create();
        $actor->assignRole(UserMode::ASSISTANT);

        foreach ([UserMode::ASSISTANT, UserMode::ADMIN] as $staffMode) {
            $target = User::factory()->create();

            $this->actingAs($actor)
                ->patch(route('admin.users.roles.update', $target), [
                    'roles' => [$staffMode],
                ])
                ->assertForbidden();

            $this->assertFalse($target->fresh()->hasRole($staffMode));
        }
    }

    public function test_an_assistant_cannot_revoke_a_staff_mode()
    {
        $this->seedRoles();

        $actor = User::factory()->create();
        $actor->assignRole(UserMode::ASSISTANT);

        $target = User::factory()->create();
        $target->assignRole(UserMode::ADMIN);

        $this->actingAs($actor)
            ->patch(route('admin.users.roles.update', $target), [
                'roles' => [UserMode::STUDENT],
            ])
            ->assertForbidden();

        $this->assertTrue($target->fresh()->hasRole(UserMode::ADMIN));
    }

    public function test_users_cannot_change_their_own_modes()
    {
        $this->seedRoles();

        $admin = User::factory()->create();
        $admin->assignRole(UserMode::ADMIN);

        $this->actingAs($admin)
            ->patch(route('admin.users.roles.update', $admin), [
                'roles' => [UserMode::STUDENT],
            ])
            ->assertForbidden();

        $this->assertTrue($admin->fresh()->hasRole(UserMode::ADMIN));
    }

    public function test_regular_users_cannot_update_roles()
    {
        $actor = $this->userWithRole(UserMode::TEACHER);
        $target = User::factory()->create();

        $this->actingAs($actor)
            ->patch(route('admin.users.roles.update', $target), [
                'roles' => [UserMode::STUDENT],
            ])
            ->assertForbidden();
    }

    public function test_unknown_modes_are_rejected()
    {
        $this->seedRoles();

        $actor = User::factory()->create();
        $actor->assignRole(UserMode::ADMIN);

        $target = User::factory()->create();

        $this->actingAs($actor)
            ->from(route('admin.users.index'))
            ->patch(route('admin.users.roles.update', $target), [
                'roles' => ['superuser'],
            ])
            ->assertSessionHasErrors('roles.0');
    }

    public function test_the_last_admin_cannot_lose_the_admin_mode()
    {
        $this->seedRoles();

        $admin = User::factory()->create();
        $admin->assignRole(UserMode::ADMIN);

        $this->assertTrue(
            AdminRoleGuard::wouldRemoveLastAdmin($admin, [UserMode::STUDENT]),
        );

        $second = User::factory()->create();
        $second->assignRole(UserMode::ADMIN);

        $this->assertFalse(
            AdminRoleGuard::wouldRemoveLastAdmin($admin, [UserMode::STUDENT]),
        );
    }
}
