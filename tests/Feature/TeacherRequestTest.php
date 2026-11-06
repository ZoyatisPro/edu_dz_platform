<?php

namespace Tests\Feature;

use App\Enums\TeacherRequestStatus;
use App\Models\TeacherRequest;
use App\Models\User;
use App\Support\UserMode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page()
    {
        $this->post(route('teacher-request.store'), [
            'motivation' => 'Je souhaite enseigner les sciences physiques.',
        ])->assertRedirect(route('login'));
    }

    public function test_a_user_can_request_the_teacher_mode()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('space'))
            ->post(route('teacher-request.store'), [
                'motivation' => 'Je souhaite enseigner les sciences physiques au lycée.',
                'subjects' => 'Sciences physiques',
            ])
            ->assertRedirect(route('space'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('teacher_requests', [
            'user_id' => $user->id,
            'subjects' => 'Sciences physiques',
            'status' => TeacherRequestStatus::Pending->value,
        ]);

        $this->assertTrue($user->fresh()->hasPendingTeacherRequest());
    }

    public function test_the_motivation_is_required_and_long_enough()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('space'))
            ->post(route('teacher-request.store'), [
                'motivation' => 'Trop court.',
            ])
            ->assertSessionHasErrors('motivation');

        $this->assertDatabaseCount('teacher_requests', 0);
    }

    public function test_a_teacher_cannot_request_the_teacher_mode()
    {
        $this->seedRoles();

        $user = User::factory()->create();
        $user->assignRole(UserMode::TEACHER);

        $this->actingAs($user)
            ->from(route('space'))
            ->post(route('teacher-request.store'), [
                'motivation' => 'Je souhaite enseigner les sciences physiques au lycée.',
            ])
            ->assertSessionHasErrors('motivation');

        $this->assertDatabaseCount('teacher_requests', 0);
    }

    public function test_a_pending_request_cannot_be_sent_twice()
    {
        $user = User::factory()->create();

        TeacherRequest::create([
            'user_id' => $user->id,
            'motivation' => 'Je souhaite enseigner les sciences physiques au lycée.',
            'status' => TeacherRequestStatus::Pending,
        ]);

        $this->actingAs($user)
            ->from(route('space'))
            ->post(route('teacher-request.store'), [
                'motivation' => 'Je souhaite enseigner les sciences physiques au lycée.',
            ])
            ->assertSessionHasErrors('motivation');

        $this->assertDatabaseCount('teacher_requests', 1);
    }

    public function test_a_user_can_send_a_new_request_after_a_rejection()
    {
        $user = User::factory()->create();

        TeacherRequest::create([
            'user_id' => $user->id,
            'motivation' => 'Première demande refusée.',
            'status' => TeacherRequestStatus::Rejected,
        ]);

        $this->actingAs($user)
            ->from(route('space'))
            ->post(route('teacher-request.store'), [
                'motivation' => 'Je souhaite enseigner les sciences physiques au lycée.',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('teacher_requests', 2);
        $this->assertTrue($user->fresh()->hasPendingTeacherRequest());
    }
}
