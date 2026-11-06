<?php

namespace App\Http\Controllers;

use App\Enums\TeacherRequestStatus;
use App\Http\Requests\TeacherRequestRequest;
use App\Models\TeacherRequest;
use App\Support\UserMode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class TeacherRequestController extends Controller
{
    /**
     * Ask the administration for the teacher mode.
     */
    public function store(TeacherRequestRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasRole(UserMode::TEACHER)) {
            throw ValidationException::withMessages([
                'motivation' => __('app.space.already_teacher'),
            ]);
        }

        if ($user->hasPendingTeacherRequest()) {
            throw ValidationException::withMessages([
                'motivation' => __('app.space.already_pending'),
            ]);
        }

        TeacherRequest::create([
            'user_id' => $user->id,
            'motivation' => $request->string('motivation')->toString(),
            'subjects' => $request->filled('subjects')
                ? $request->string('subjects')->toString()
                : null,
            'status' => TeacherRequestStatus::Pending,
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('app.space.sent'),
        ]);

        return back();
    }
}
