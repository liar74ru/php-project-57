<?php

namespace Http\Controllers;

use App\Models\Label;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        // Создаем несколько задач для отображения
        $task1 = $this->createTask(['name' => 'Task 1']);
        $task2 = $this->createTask(['name' => 'Task 2']);

        $response = $this->get(route('tasks.index'));
        $response->assertStatus(200);
        $response->assertSee(__('Task'));
        $response->assertSee('Task 1');
        $response->assertSee('Task 2');
    }

    public function testCreate()
    {
        // Для доступа к форме создания нужен авторизованный пользователь
        $user = $this->createTestUser();
        $this->actingAs($user);

        $response = $this->get(route('tasks.create'));
        $response->assertStatus(200);
        $response->assertSee(__('Create task'));
    }

    public function testStoreCreateNewTask()
    {
        $user = $this->createTestUser();
        $status = $this->createTaskStatus();

        // Данные для создания задачи
        $taskData = [
            'name' => 'New Test Task',
            'description' => 'Test Description',
            'status_id' => $status->id,
            'assigned_to_id' => $user->id,
        ];

        // Авторизуем пользователя
        $this->actingAs($user);

        // Отправляем POST запрос
        $response = $this->post(route('tasks.store'), $taskData);

        // Проверяем редирект
        $response->assertRedirect(route('tasks.index'));

        // Проверяем, что задача создалась в БД
        $this->assertDatabaseHas('tasks', [
            'name' => 'New Test Task',
            'status_id' => $status->id,
            'created_by_id' => $user->id,
            'assigned_to_id' => $user->id,
        ]);
    }

    public function testShow()
    {
        // Создаем задачу через хелпер
        $task = $this->createTask(['name' => 'Specific Task for Show']);

        // Получаем пользователя, который создал задачу
        $user = User::find($task->created_by_id);

        // Авторизуемся
        $this->actingAs($user);

        // Используем именованный маршрут
        $response = $this->get(route('tasks.show', $task));

        $response->assertStatus(200);
        $response->assertSee($task->name);
        $response->assertSee($task->description);
//        $response->assertSee(__('Edit'));
//        $response->assertSee(__('Delete'));
    }

    // Дополнительные тесты, которые могут быть полезны:

    public function testEdit()
    {
        // Сначала создаем пользователя
        $user = $this->createTestUser();

        // Создаем задачу, передавая created_by_id явно
        $task = $this->createTask([
            'created_by_id' => $user->id,
            'assigned_to_id' => $user->id,
        ]);

        // Теперь мы точно знаем, какой пользователь создал задачу
        $this->actingAs($user);
        $response = $this->get(route('tasks.edit', $task));

        $response->assertStatus(200);
        $response->assertSee(__('Edit'));
        $response->assertSee($task->name);
    }

    public function testUpdate()
    {
        // 1. Создаем пользователя-создателя задачи
        $creator = $this->createTestUser();

        // 2. Создаем другого пользователя (который будет редактировать)
        $editor = $this->createTestUser([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
        ]);

        // 3. Создаем задачу от имени создателя
        $task = Task::factory()->create([
            'name' => 'Original Task',
            'created_by_id' => $creator->id,
            'assigned_to_id' => $creator->id,
        ]);

        // 4. Создаем новый статус для обновления
        $newStatus = $this->createTaskStatus(['name' => 'In Progress']);

        // 5. Данные для обновления
        $updateData = [
            'name' => 'Updated by Another User',
            'description' => 'Updated by different user',
            'status_id' => $newStatus->id,
            'assigned_to_id' => $editor->id, // Может переназначить на себя
        ];

        // 6. Авторизуемся как РЕДАКТОР (не создатель!)
        $this->actingAs($editor);

        // 7. Пытаемся обновить задачу
        $response = $this->put(route('tasks.update', $task), $updateData);

        // 8. Проверяем результат
        // Если разрешено любому авторизованному:
        $response->assertRedirect(route('tasks.index'));

        // 9. Проверяем, что задача обновилась
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Updated by Another User',
            'status_id' => $newStatus->id,
            'assigned_to_id' => $editor->id, // Переназначена на редактора
            'created_by_id' => $creator->id, // Создатель остался прежним!
        ]);
    }

    public function testUpdateFailsForUnauthenticatedUser()
    {
        $creator = $this->createTestUser();
        $task = $this->createTask(['created_by_id' => $creator->id]);

        $updateData = ['name' => 'Try to update'];

        // НЕ авторизуемся
        $response = $this->put(route('tasks.update', $task), $updateData);

        // Должен быть редирект на login или 403
        $response->assertRedirect('/'); // Или assertStatus(403)
    }

    public function testDestroy()
    {
        $task = $this->createTask();
        $user = User::find($task->created_by_id);

        $this->actingAs($user);
        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function testStoreCreateNewTaskWithLabels()
    {
        $user = $this->createTestUser();
        $status = $this->createTaskStatus();

        // Создаем несколько меток
        $label1 = Label::create(['name' => 'Важная']);
        $label2 = Label::create(['name' => 'Срочно']);
        $label3 = Label::create(['name' => 'Баг']);

        // Данные для создания задачи с метками
        $taskData = [
            'name' => 'New Task With Labels',
            'description' => 'Test Description With Labels',
            'status_id' => $status->id,
            'assigned_to_id' => $user->id,
            'labels' => [$label1->id, $label2->id] // Прикрепляем 2 метки из 3
        ];

        $this->actingAs($user);

        $response = $this->post(route('tasks.store'), $taskData);

        $response->assertRedirect(route('tasks.index'));

        // Проверяем, что задача создалась
        $task = Task::where('name', 'New Task With Labels')->first();
        $this->assertNotNull($task);

        // Проверяем, что метки прикрепились
        $this->assertCount(2, $task->labels);
        $this->assertTrue($task->labels->contains('name', 'Важная'));
        $this->assertTrue($task->labels->contains('name', 'Срочно'));
        $this->assertFalse($task->labels->contains('name', 'Баг')); // Эта не должна прикрепиться

        // Проверяем связи в pivot таблице
        $this->assertDatabaseHas('label_task', [
            'task_id' => $task->id,
            'label_id' => $label1->id,
        ]);

        $this->assertDatabaseHas('label_task', [
            'task_id' => $task->id,
            'label_id' => $label2->id,
        ]);

        $this->assertDatabaseMissing('label_task', [
            'task_id' => $task->id,
            'label_id' => $label3->id,
        ]);
    }
}
