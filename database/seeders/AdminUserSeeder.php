<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\UserMode;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Create the main administrator account from environment settings.
     */
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => config('app.admin.email')],
            [
                'name' => config('app.admin.name'),
                'password' => config('app.admin.password'),
                'locale' => 'fr',
                'email_verified_at' => now(),
            ],
        );

        $admin->assignRole(UserMode::ADMIN);
    }
}
