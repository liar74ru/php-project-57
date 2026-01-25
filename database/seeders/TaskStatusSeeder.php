<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['новая', 'завершена', 'выполняется', 	'в архиве'];
        foreach ($statuses as $status) {
            TaskStatus::firstOrCreate(['name' => $status]);
        }
    }
}
