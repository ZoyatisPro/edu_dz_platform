<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\UserMode;
use Illuminate\Database\Seeder;

class DemoUsersSeeder extends Seeder
{
    /**
     * Create one fake account per mode for local demonstrations.
     */
    public function run(): void
    {
        if (app()->isProduction()) {
            return;
        }

        $accounts = [
            UserMode::ASSISTANT => ['name' => 'Assistante de démonstration', 'email' => 'assistant@edu-dz.test'],
            UserMode::TEACHER => ['name' => 'Prof de démonstration', 'email' => 'teacher@edu-dz.test'],
            UserMode::STUDENT => ['name' => 'Élève de démonstration', 'email' => 'student@edu-dz.test'],
            UserMode::PARENT => ['name' => 'Parent de démonstration', 'email' => 'parent@edu-dz.test'],
        ];

        foreach ($accounts as $mode => $attributes) {
            $user = User::query()->firstOrCreate(
                ['email' => $attributes['email']],
                [
                    ...$attributes,
                    'password' => 'password',
                    'locale' => 'fr',
                    'email_verified_at' => now(),
                ],
            );

            $user->assignRole($mode);
        }
    }
}
