<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Label;

class TaskLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = Task::all();
        $labels = Label::all();

        foreach ($tasks as $task) {
            // Случайное количество меток для задачи (от 0 до 3)
            $randomLabels = $labels->random(rand(0, min(4, $labels->count())));

            foreach ($randomLabels as $label) {
                // Используем sync без удаления, чтобы добавить связи
                $task->labels()->syncWithoutDetaching([$label->id]);
            }
        }
    }
}
