<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\ActiveMode;
use App\Support\UserMode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ModeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page()
    {
        $this->post(route('modes.activate', ['mode' => UserMode::STUDENT]))
            ->assertRedirect(route('login'));
    }

    public function test_student_and_parent_modes_can_be_activated_freely()
    {
        $this->seedRoles();

        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('space'))
            ->post(route('modes.activate', ['mode' => UserMode::STUDENT]))
            ->assertRedirect(route('space'))
            ->assertSessionHasNoErrors();

        $this->assertTrue($user->fresh()->hasRole(UserMode::STUDENT));
        $this->assertSame(UserMode::STUDENT, session(ActiveMode::SESSION_KEY));

        $this->actingAs($user)
            ->from(route('space'))
            ->post(route('modes.activate', ['mode' => UserMode::PARENT]))
            ->assertRedirect(route('space'))
            ->assertSessionHasNoErrors();

        $this->assertTrue($user->fresh()->hasRole(UserMode::PARENT));
        $this->assertSame(UserMode::PARENT, session(ActiveMode::SESSION_KEY));
    }

    public function test_teacher_mode_cannot_be_activated_without_approval()
    {
        $this->seedRoles();

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('modes.activate', ['mode' => UserMode::TEACHER]))
            ->assertForbidden();

        $this->assertFalse($user->fresh()->hasRole(UserMode::TEACHER));
    }

    public function test_staff_modes_cannot_be_activated_by_a_regular_user()
    {
        $this->seedRoles();

        $user = User::factory()->create();

        foreach ([UserMode::ASSISTANT, UserMode::ADMIN] as $mode) {
            $this->actingAs($user)
                ->post(route('modes.activate', ['mode' => $mode]))
                ->assertForbidden();

            $this->assertFalse($user->fresh()->hasRole($mode));
        }
    }

    public function test_unknown_modes_return_a_not_found_response()
    {
        $this->seedRoles();

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/modes/superuser/activate')
            ->assertNotFound();
    }

    public function test_users_can_switch_between_the_modes_they_own()
    {
        $this->seedRoles();

        $user = User::factory()->create();
        $user->assignRole([UserMode::STUDENT, UserMode::PARENT]);

        $this->actingAs($user)
            ->post(route('modes.switch', ['mode' => UserMode::PARENT]))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasNoErrors();

        $this->assertSame(UserMode::PARENT, session(ActiveMode::SESSION_KEY));
    }

    public function test_users_cannot_switch_to_a_mode_they_do_not_own()
    {
        $this->seedRoles();

        $user = User::factory()->create();
        $user->assignRole(UserMode::STUDENT);

        $this->actingAs($user)
            ->post(route('modes.switch', ['mode' => UserMode::ADMIN]))
            ->assertForbidden();

        $this->assertNull(session(ActiveMode::SESSION_KEY));
    }

    public function test_the_default_active_mode_follows_the_priority_order()
    {
        $this->seedRoles();

        $user = User::factory()->create();
        $user->assignRole([UserMode::STUDENT, UserMode::TEACHER, UserMode::ADMIN]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('activeMode', UserMode::ADMIN)
                ->where('auth.user.active_mode', UserMode::ADMIN),
            );
    }

    public function test_the_session_mode_wins_over_the_default()
    {
        $this->seedRoles();

        $user = User::factory()->create();
        $user->assignRole([UserMode::STUDENT, UserMode::TEACHER]);

        $this->actingAs($user)
            ->withSession([ActiveMode::SESSION_KEY => UserMode::STUDENT])
            ->get(route('dashboard'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('activeMode', UserMode::STUDENT),
            );
    }

    public function test_authenticated_users_can_open_their_personal_space()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('space'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('space'));
    }
}
