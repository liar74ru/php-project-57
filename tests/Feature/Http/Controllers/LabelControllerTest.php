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
        $response = $this->get('/labels/create');
        $response->assertRedirect('login');
        $response->assertStatus(302);

        $response = $this->get('/labels/store');
        $response->assertRedirect('login');
        $response->assertStatus(302);

        $response = $this->get('/labels/edit');
        $response->assertRedirect('login');
        $response->assertStatus(302);

        $response = $this->get('/labels/update');
        $response->assertRedirect('login');
        $response->assertStatus(302);

        $response = $this->get('/labels/destroy');
        $response->assertRedirect('login');
        $response->assertStatus(302);

    }

    public function testIndex()
    {
        $response = $this->get('labels');

        $response->assertStatus(200);

        $response->assertSee(__('label'));
    }

    public function testCreate()
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        $response = $this->get('/labels/create');
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

        $response = $this->post('/labels', $data);

        // 3. Проверяем, что произошел редирект
        $response->assertRedirect('/labels');

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
        $response = $this->post('/labels', [
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
        $response = $this->post('/labels', $data);
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
        $response = $this->get("/labels/{$label->id}/edit");

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

        $response = $this->patch("/labels/{$id}", $data);

        // 3. Проверяем, что произошел редирект
        $response->assertRedirect('/labels');

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

        $response = $this->delete("/labels/{$id}");
        $response->assertRedirect('/labels');

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

        $response = $this->delete("/labels/{$id}");

        $response->assertRedirect('/labels');

        $this->assertDatabaseHas('labels', [
            'id' => $id,
            'name' => 'Метка для удаления'
        ]);

        $this->assertDatabaseCount('labels', $initialCount);
    }

    public function testStoreCreateNewLabelWithFlashMessage(): void
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        $data = [
            'name' => 'Новая метка',
            'description' => 'Какое-то описание'
        ];

        $response = $this->post('/labels', $data);

        $response->assertRedirect('/labels');

        // Проверяем, что flash сообщение присутствует в сессии
        $response->assertSessionHas('flash_notification');

        // Проверяем содержимое через followRedirect
        $this->get('/labels')
            ->assertSee('Метка успешно создана');

        $this->assertDatabaseHas('labels', $data);
    }

    public function testUpdateOkWithFlashMessage(): void
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        $OldName = 'Метка для редактирования';
        $NewName = 'Новая метка';

        $label = Label::create(['name' => $OldName]);
        $id = $label->id;

        $response = $this->patch("/labels/{$id}", ['name' => $NewName]);

        $response->assertRedirect('/labels');

        // Проверяем flash сообщение
        $flash = session('flash_notification');
        $this->assertNotEmpty($flash);

        $firstMessage = $flash[0];
        $this->assertEquals('success', $firstMessage['level']);
        $this->assertEquals('Метка успешно изменена', $firstMessage['message']);

        $this->assertDatabaseHas('labels', ['id' => $id, 'name' => $NewName]);
        $this->assertDatabaseMissing('labels', ['name' => $OldName]);
    }

    public function testDeleteOkWithFlashMessage(): void
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        $label = Label::create(['name' => 'Метка для удаления']);
        $id = $label->id;

        $response = $this->delete("/labels/{$id}");

        $response->assertRedirect('/labels');

        // Проверяем flash сообщение
        $flash = session('flash_notification');
        $this->assertNotEmpty($flash);

        $firstMessage = $flash[0];
        $this->assertEquals('success', $firstMessage['level']);
        $this->assertEquals('Метка успешно удалена', $firstMessage['message']);

        $this->assertDatabaseCount('labels', 0);
    }

    public function testStoreNotCreateNewLabelWithValidationMessage(): void
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        $response = $this->post('/labels', ['name' => '']);

        $response->assertSessionHasErrors(['name']);

        // Flash сообщение об ошибке (если есть)
        $flash = session('flash_notification', []);
        if (!empty($flash)) {
            $this->assertEquals('danger', $flash[0]['level']);
            $this->assertEquals('Это обязательное поле', $flash[0]['message']);
        }

        $this->assertDatabaseCount('labels', 0);
    }

    public function testStoreSomeNameLabelsWithValidationMessage(): void
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        $data = ['name' => 'Новая метка'];
        Label::create($data);

        $response = $this->post('/labels', $data);

        $response->assertSessionHasErrors(['name']);

        // Flash сообщение об ошибке (если есть)
        $flash = session('flash_notification', []);
        if (!empty($flash)) {
            $this->assertEquals('danger', $flash[0]['level']);
            $this->assertEquals('Метка с таким именем уже существует', $flash[0]['message']);
        }

        $this->assertDatabaseCount('labels', 1);
    }

    // ============= ТЕСТЫ ДЛЯ ПРОВЕРКИ НА СТРАНИЦЕ =============

    public function testFlashMessageAppearsOnPage(): void
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        // Создаем метку и проверяем, что сообщение видно на странице
        $data = ['name' => 'Тестовая метка'];

        // Отправляем POST запрос
        $this->post('/labels', $data);

        // Переходим на страницу меток (после редиректа)
        $response = $this->get('/labels');

        // Проверяем, что флеш сообщение отображается
        $response->assertSee('Метка успешно создана');
    }

    public function testDeleteFlashMessageAppearsOnPage(): void
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        // Создаем метку
        $label = Label::create(['name' => 'Метка для удаления']);

        // Удаляем метку
        $this->delete("/labels/{$label->id}");

        // Переходим на страницу меток
        $response = $this->get('/labels');

        // Проверяем, что флеш сообщение отображается
        $response->assertSee('Метка успешно удалена');
    }

    // ============= ХЕЛПЕР ДЛЯ ПРОВЕРКИ FLASH =============

    /**
     * Проверяет наличие flash сообщения
     */
    protected function assertFlashMessage(string $message, string $level = 'success'): void
    {
        $flash = session('flash_notification', []);

        $found = false;
        foreach ($flash as $item) {
            if (isset($item['message']) && $item['message'] === $message
                && isset($item['level']) && $item['level'] === $level) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found, "Flash message '{$message}' with level '{$level}' not found");
    }

    /**
     * Проверяет, что нет flash сообщений
     */
    protected function assertNoFlashMessages(): void
    {
        $flash = session('flash_notification', []);
        $this->assertEmpty($flash, 'Unexpected flash messages found');
    }

    // Используем хелперы в тестах
    public function testStoreCreateNewLabelWithFlashHelper(): void
    {
        $user = $this->createTestUser();
        $this->actingAs($user);

        $data = ['name' => 'Тестовая метка'];

        $response = $this->post('/labels', $data);
        $response->assertRedirect('/labels');

        // Используем хелпер
        $this->assertFlashMessage('Метка успешно создана');

        $this->assertDatabaseHas('labels', $data);
    }
}
