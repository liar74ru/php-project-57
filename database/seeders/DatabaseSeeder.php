<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::firstOrCreate(
            ['email' => 'q@q'],
            [
                'name' => 'Test User',
                'password' => '12345678',
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            TaskStatusSeeder::class
            // TaskSeeder::class,
            // LabelSeeder::class,
        ]);
    }
}
