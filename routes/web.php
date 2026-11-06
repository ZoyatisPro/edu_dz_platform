<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ModeController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\TeacherRequestController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::post('locale/{locale}', [LocaleController::class, 'update'])
    ->name('locale.update');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
    Route::get('space', [SpaceController::class, 'index'])->name('space');

    Route::post('modes/{mode}/activate', [ModeController::class, 'activate'])
        ->name('modes.activate');
    Route::post('modes/{mode}/switch', [ModeController::class, 'switch'])
        ->name('modes.switch');

    Route::post('teacher-request', [TeacherRequestController::class, 'store'])
        ->name('teacher-request.store');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('users', [UserController::class, 'index'])
            ->middleware('can:viewAny,App\Models\User')
            ->name('users.index');

        Route::patch('users/{user}/roles', [UserRoleController::class, 'update'])
            ->middleware('can:manageRoles,user')
            ->name('users.roles.update');
    });
});

require __DIR__.'/settings.php';
