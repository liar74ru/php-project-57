<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase; // Очищает базу перед каждым тестом

    /**
     * 1. Тест создания пользователя
     * Проверяем, что можем создать пользователя с обязательными полями
     */
    public function testUserCanBeCreated()
    {
        // Arrange: Подготовка данных
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ];

        // Act: Действие (создание пользователя)
        $user = $this->createTestUser($userData);

        // Assert: Проверка результата
        $this->assertInstanceOf(User::class, $user); // Проверяем тип объекта
        $this->assertEquals('Test User', $user->name); // Проверяем имя
        $this->assertEquals('test@example.com', $user->email); // Проверяем email

        // Проверяем, что пользователь сохранен в базе данных
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    /**
     * 2. Тест уникальности email
     * Email должен быть уникальным в системе
     */
    public function testEmailMustBeUnique()
    {
        // Создаем первого пользователя
        $this->createTestUser([
            'email' => 'duplicate@example.com',
        ]);

        // Пытаемся создать второго с таким же email
        // Ожидаем исключение (нарушение уникальности)
        $this->expectException(\Illuminate\Database\QueryException::class);

        $this->createTestUser([
            'email' => 'duplicate@example.com', // Дублирующий email!
        ]);
    }

    /**
     * 3. Тест обязательных полей
     * Имя и email должны быть обязательными
     */
    public function testNameAndEmailAreRequired()
    {
        // Пытаемся создать пользователя без имени
        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'email' => 'test@example.com',
            'password' => 'password',
            // Нет имени!
        ]);

        // Пытаемся создать пользователя без email
        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'name' => 'Test User',
            'password' => 'password',
            // Нет email!
        ]);
    }

    /**
     * 4. Тест связи "созданные задачи"
     * Пользователь может создавать задачи
     */
    public function testUserHasCreatedTasks()
    {
        // Создаем пользователя
        $user = $this->createTestUser();

        // Создаем статус для задачи
        $status = $this->createTaskStatus();

        // Создаем задачу от имени пользователя
        $task = $this->createTask([
            'created_by_id' => $user->id,
            'status_id' => $status->id,
        ]);

        // Проверяем, что задача доступна через отношение createdTasks
        $this->assertTrue($user->createdTasks->contains($task));
        $this->assertEquals(1, $user->createdTasks->count());
        $this->assertEquals($task->name, $user->createdTasks->first()->name);
    }

    /**
     * 5. Тест связи "назначенные задачи"
     * Пользователю могут быть назначены задачи
     */
    public function testUserHasAssignedTasks()
    {
        $user = $this->createTestUser();
        $status = $this->createTaskStatus();

        $task = $this->createTask([
            'created_by_id' => $user->id,
            'status_id' => $status->id,
            'assigned_to_id' => $user->id,
        ]);

        $this->assertTrue($user->assignedTasks->contains($task));
        $this->assertEquals(1, $user->assignedTasks->count());
    }

    /**
     * 6. Тест fillable полей
     * Проверяем, какие поля можно массово назначать
     */
    public function testFillableFields()
    {
        $user = new User();

        $expectedFillable = ['name', 'email', 'password'];

        // Проверяем, что fillable поля соответствуют модели
        $this->assertEquals($expectedFillable, $user->getFillable());
    }

    /**
     * 7. Тест hidden полей
     * Пароль и токен не должны показываться при сериализации
     */
    public function testHiddenFields()
    {
        $user = new User();

        $expectedHidden = ['password', 'remember_token'];

        $this->assertEquals($expectedHidden, $user->getHidden());
    }

    /**
     * 8. Тест casts (приведение типов)
     * Проверяем, что пароль хэшируется автоматически
     */
    public function testPasswordIsHashed()
    {
        $user = $this->createTestUser([
            'password' => 'plain_password', // Пароль в открытом виде
        ]);

        // Пароль должен быть автоматически захэширован
        $this->assertNotEquals('plain_password', $user->password);
        $this->assertTrue(password_verify('plain_password', $user->password));
    }

    /**
     * 9. Тест обновления пользователя
     */
    public function testUserCanBeUpdated()
    {
        // Создаем пользователя
        $user = $this->createTestUser(['name' => 'Old Name']);

        // Обновляем имя
        $user->update(['name' => 'New Name']);

        // Обновляем объект из базы
        $user->refresh();

        // Проверяем, что имя изменилось
        $this->assertEquals('New Name', $user->name);

        // Проверяем в базе данных
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
        ]);
    }

    /**
     * 10. Тест удаления пользователя
     */
    public function testUserCanBeDeleted()
    {
        $user = $this->createTestUser();
        $userId = $user->id;

        // Удаляем пользователя
        $user->delete();

        // Проверяем, что пользователя нет в базе
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }
}
