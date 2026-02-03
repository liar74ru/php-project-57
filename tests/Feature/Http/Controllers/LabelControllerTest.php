<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabelControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testNotAuthenticated()
    {
        $response = $this->get('/label/create');
        $response->assertRedirect('login');
        $response->assertStatus(302);

        $response = $this->get('/label/store');
        $response->assertRedirect('login');
        $response->assertStatus(302);

        $response = $this->get('/label/edit');
        $response->assertRedirect('login');
        $response->assertStatus(302);

        $response = $this->get('/label/update');
        $response->assertRedirect('login');
        $response->assertStatus(302);

        $response = $this->get('/label/destroy');
        $response->assertRedirect('login');
        $response->assertStatus(302);

    }

    public function testIndex()
    {
        $response = $this->get('label');

        $response->assertStatus(200);

        $response->assertSee(__('label'));
    }

    public function testCreate()
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        $response = $this->get('/label/create');
        $response->assertStatus(200);
    }

    public function testStoreCreateNewLabel()
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        // 1. Подготавливаем данные для создания
        $data = [
            'name' => 'Новая метка',
            'description' => 'Какое-то описание'
        ];

        $response = $this->post('/label', $data);

        // 3. Проверяем, что произошел редирект
        $response->assertRedirect('/label');

        // 4. Проверяем, что статус добавился в базу
        $this->assertDatabaseHas('labels', [
            'name' => 'Новая метка',
            'description' => 'Какое-то описание'
        ]);
    }

    public function testStoreNotCreateNewLabel()
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        // 1. Пытаемся создать метку без имени
        $response = $this->post('/label', [
            'name' => '' // Пустое имя
        ]);

        // 2. Проверяем, что есть ошибка валидации
        $response->assertSessionHasErrors(['name']);

        // 3. Проверяем, что в базе ничего не добавилось
        $this->assertDatabaseCount('labels', 0);
    }

    public function testStoreSomeNameLabels()
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        $data = [
            'name' => 'Новая метка'
        ];

        Label::create($data);
        $initialCount = Label::count();
        $response = $this->post('/label', $data);
        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseCount('labels', $initialCount);
    }

    public function testEditOk()
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        // 1. Создаем тестовый статус
        $label = Label::create([
            'name' => 'Метка для редактирования'
        ]);

        // 2. Переходим на страницу редактирования
        $response = $this->get("/label/{$label->id}/edit");

        // 3. Проверяем успешный ответ
        $response->assertStatus(200);

        // 4. Проверяем правильный шаблон
        $response->assertViewIs('label.edit');

        // 5. Проверяем, что данные переданы в шаблон
        $response->assertViewHas('label', $label);

        // 6. Проверяем, что видим имя статуса на странице
        $response->assertSee(__('Edit label'));
    }

    public function testUpdateOk()
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        $OldName = 'Метка для редактирования';
        $NewName = 'Новая метка';

        $label = Label::create([
            'name' => $OldName
        ]);

        $id = $label->id;
        $initialCount = Label::count();

        // 1. Подготавливаем данные для обновления
        $data = [
            'name' => $NewName
        ];

        $response = $this->patch("/label/{$id}", $data);

        // 3. Проверяем, что произошел редирект
        $response->assertRedirect('/label');

        // 4. Проверяем, что статус добавился в базу
        $this->assertDatabaseHas('labels', [
            'id' => $id,
            'name' => $NewName
        ]);

        $this->assertDatabaseMissing('labels', [
            'name' => $OldName
        ]);

        $this->assertDatabaseCount('labels', $initialCount);
    }

    public function testDeleteOk()
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        $label = Label::create([
            'name' => 'Метка для удаления'
        ]);

        $id = $label->id;

        $response = $this->delete("/label/{$id}");
        $response->assertRedirect('/label');

        $this->assertDatabaseCount('labels', 0);
    }

    public function testDeleteUseLabel()
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        $task = $this->createTask();

        $label = Label::create([
            'name' => 'Метка для удаления'
        ]);

        $id = $label->id;

        $task->labels()->attach($id);

        $initialCount = Label::count();

        $response = $this->delete("/label/{$id}");

        $response->assertRedirect('/label');

        $this->assertDatabaseHas('labels', [
            'id' => $id,
            'name' => 'Метка для удаления'
        ]);

        $this->assertDatabaseCount('labels', $initialCount);
    }
}
