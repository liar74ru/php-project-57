<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'status_id' => TaskStatus::factory(),
            'created_by_id' => User::factory(),
            'assigned_to_id' => User::factory(),
        ];
    }

    // Всего один дополнительный метод для задач без исполнителя
    public function withoutAssignee(): static
    {
        return $this->state(fn (array $attributes) => [
            'assigned_to_id' => null,
        ]);
    }
}
