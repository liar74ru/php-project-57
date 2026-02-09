<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест 1: Проверка создания задачи
     */
    public function testTaskCanBeCreated()
    {
        $task = $this->createTask();

        // Проверяем результаты
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);

        // Проверяем, что у задачи есть имя (не пустое)
        $this->assertNotEmpty($task->name);
    }

    /**
     * Тест 2: Проверка связи задачи со статусом
     */
    public function testTaskBelongsToStatus()
    {
        // Создаем задачу
        $task = $this->createTask();

        // Проверяем связь со статусом
        // 1. Проверяем тип объекта - должен быть TaskStatus
        $this->assertInstanceOf(\App\Models\TaskStatus::class, $task->status);

        // 2. Проверяем, что внешний ключ (status_id) ссылается на правильный объект
        $this->assertEquals($task->status_id, $task->status->id);

        $this->assertNotEmpty($task->status->name);
    }

    /**
     * Тест 3: Проверка связи задачи с создателем (creator)
     */
    public function testTaskBelongsToCreator()
    {
        // Создаем задачу
        $task = $this->createTask();

        // Проверяем связь с создателем
        // 1. Проверяем тип объекта - должен быть User
        $this->assertInstanceOf(\App\Models\User::class, $task->creator);

        // 2. Проверяем, что внешний ключ ссылается на правильный объект
        $this->assertEquals($task->created_by_id, $task->creator->id);

        // 3. Дополнительная проверка: метод createdBy() должен работать аналогично
        $this->assertEquals($task->creator->id, $task->createdBy->id);
    }

    /**
     * Тест 4: Проверка связи задачи с исполнителем (assignee)
     */
    public function testTaskBelongsToAssignee()
    {
        // Создаем задачу
        $task = $this->createTask();

        // Проверяем связь с исполнителем
        // 1. Проверяем тип объекта - должен быть User
        $this->assertInstanceOf(\App\Models\User::class, $task->assignee);

        // 2. Проверяем, что внешний ключ ссылается на правильный объект
        $this->assertEquals($task->assigned_to_id, $task->assignee->id);

        // 3. Дополнительная проверка: метод assignedTo() должен работать аналогично
        $this->assertEquals($task->assignee->id, $task->assignedTo->id);
    }
}
