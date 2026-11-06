<?php

namespace App\Http\Controllers;

use App\Support\ActiveMode;
use App\Support\UserMode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ModeController extends Controller
{
    /**
     * Activate a mode the user may enable by themselves.
     */
    public function activate(Request $request, string $mode): RedirectResponse
    {
        abort_unless(in_array($mode, UserMode::ALL, true), 404);
        abort_unless(UserMode::isSelfService($mode), 403);

        $user = $request->user();

        if (! $user->hasRole($mode)) {
            $user->assignRole($mode);
        }

        ActiveMode::set($request, $mode);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('app.modes.activated', [
                'mode' => trans("app.modes.{$mode}"),
            ]),
        ]);

        return back();
    }

    /**
     * Switch the active mode.
     */
    public function switch(Request $request, string $mode): RedirectResponse
    {
        abort_unless(in_array($mode, UserMode::ALL, true), 404);
        abort_unless($request->user()->hasRole($mode), 403);

        ActiveMode::set($request, $mode);

        return to_route('dashboard');
    }
}
