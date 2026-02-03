<?php

namespace Tests;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // Используем фабрики вместо ручного создания
    protected function createTestUser(array $attributes = [])
    {
        return User::factory()->create(array_merge([
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ], $attributes));
        // Фабрика автоматически создает уникальный email
    }

    protected function createTaskStatus(array $attributes = [])
    {
        return TaskStatus::factory()->create($attributes);
    }

    protected function createTask(array $attributes = [])
    {
        $user = $this->createTestUser();
        $status = $this->createTaskStatus();

        return Task::factory()->create(array_merge([
            'status_id' => $status->id,
            'assigned_to_id' => $user->id,
            'created_by_id' => $user->id
        ], $attributes));
    }
}
