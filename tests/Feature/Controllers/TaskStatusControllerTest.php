<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskStatusControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get('task_statuses');

        $response->assertStatus(200);

        $response->assertSee('Статусы');
    }

    public function testCreate()
    {
        $response = $this->get('/task_statuses/create');
        $response->assertStatus(200);
    }

    public function testStoreCreateNewTaskStatus()
    {
        // 1. Подготавливаем данные для создания
        $data = [
            'name' => 'Новый статус'
        ];

        $response = $this->post('/task_statuses', $data);

        // 3. Проверяем, что произошел редирект
        $response->assertRedirect('/task_statuses');

        // 4. Проверяем, что статус добавился в базу
        $this->assertDatabaseHas('task_statuses', [
            'name' => 'Новый статус'
        ]);
    }

    public function testStoreNotCreateNewTaskStatus()
    {
        // 1. Пытаемся создать статус без имени
        $response = $this->post('/task_statuses', [
            'name' => '' // Пустое имя
        ]);

        // 2. Проверяем, что есть ошибка валидации
        $response->assertSessionHasErrors(['name']);

        // 3. Проверяем, что в базе ничего не добавилось
        $this->assertDatabaseCount('task_statuses', 0);
    }

    public function testStoreSomeCreateTaskStatus()
    {
        $data = [
            'name' => 'Новый статус'
        ];

        TaskStatus::create($data);

        $response = $this->post('/task_statuses', $data);
        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseCount('task_statuses', 1);
    }

    public function testEditOk()
    {
        // 1. Создаем тестовый статус
        $taskStatus = TaskStatus::create([
            'name' => 'Статус для редактирования'
        ]);

        // 2. Переходим на страницу редактирования
        $response = $this->get("/task_statuses/{$taskStatus->id}/edit");

        // 3. Проверяем успешный ответ
        $response->assertStatus(200);

        // 4. Проверяем правильный шаблон
        $response->assertViewIs('task-status.edit');

        // 5. Проверяем, что данные переданы в шаблон
        $response->assertViewHas('task_status', $taskStatus);

        // 6. Проверяем, что видим имя статуса на странице
        $response->assertSee('Изменение Статуса');
    }

    public function testUpdateOk()
    {
        $taskStatus = TaskStatus::create([
            'name' => 'Статус для редактирования'
        ]);

        $id = $taskStatus->id;

        // 1. Подготавливаем данные для обновления
        $data = [
            'name' => 'Новый статус'
        ];

        $response = $this->patch("/task_statuses/{$taskStatus->id}", $data);

        // 3. Проверяем, что произошел редирект
        $response->assertRedirect('/task_statuses');

        // 4. Проверяем, что статус добавился в базу
        $this->assertDatabaseHas('task_statuses', [
            'id' => $id,
            'name' => 'Новый статус'
        ]);

        $this->assertDatabaseMissing('task_statuses', [
            'name' => 'Статус для редактирования'
        ]);

        $this->assertDatabaseCount('task_statuses', 1);
    }

    public function testDeleteOk()
    {
        $taskStatus = TaskStatus::create([
            'name' => 'Статус для удаления'
        ]);

        $id = $taskStatus->id;

        $response = $this->delete("/task_statuses/{$taskStatus->id}");
        $response->assertRedirect('/task_statuses');

        $this->assertDatabaseCount('task_statuses', 0);
    }
}
