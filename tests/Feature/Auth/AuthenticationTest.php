<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('home', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_auth_works_full_cycle(): void
    {
        $userData = [
            'name' => 'Toto',
            'email' => 'toto@hexlet.io',
            'password' => 'awesomeness',
            'password_confirmation' => 'awesomeness',
        ];

        // Регистрация
        $response = $this->post('/register', $userData);
        $response->assertRedirect('/');
        $this->assertAuthenticated();

        // Выход
        $response = $this->actingAs(User::where('email', 'toto@hexlet.io')->first())
            ->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();

        // Вход
        $response = $this->post('/login', [
            'email' => 'toto@hexlet.io',
            'password' => 'awesomeness',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticated();
    }

    public function test_register_form_validation(): void
    {
        $userData = [
            'name' => 'Toto',
            'email' => 'toto@hexlet.io',
        ];

        // Тест: короткий пароль (7 символов)
        $response = $this->post('/register', array_merge($userData, [
            'password' => 'awesome', // 7 символов
            'password_confirmation' => 'awesome',
        ]));

        $response->assertSessionHasErrors(['password']);
        $response->assertSessionHasErrors([
            'password' => 'Пароль должен иметь длину не менее 8 символов'
        ]);

        // Тест: пароли не совпадают
        $response = $this->post('/register', array_merge($userData, [
            'password' => 'awesomeness',
            'password_confirmation' => 'hexlet', // разные
        ]));

        $response->assertSessionHasErrors(['password']);
        $response->assertSessionHasErrors([
            'password' => 'Пароль и подтверждение не совпадают'
        ]);
    }

    public function test_login_form_validation_shows_error_message(): void
    {
        // Тест: неверные учетные данные показывают сообщение
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertSessionHasErrors([
            'email' => 'Введите правильные имя пользователя и пароль'
        ]);
    }
}
