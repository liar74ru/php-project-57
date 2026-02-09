<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

class EmailVerificationNotificationControllerTest extends TestCase
{
    use RefreshDatabase; // Добавляем эту строку

    public function testVerifiedUserRedirectedToHome()
    {
        // Создаем пользователя с подтвержденным email
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->post(route('verification.send'));

        $response->assertRedirect(route('home'));
    }

    public function testSendsVerificationEmailToUnverifiedUser()
    {
        // Отключаем реальные уведомления
        Notification::fake();

        // Создаем пользователя без подтверждения email
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('verification.send'));

        // Проверяем редирект обратно
        $response->assertRedirect();
        $response->assertSessionHas('status', 'verification-link-sent');

        // Проверяем, что уведомление было отправлено
        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );
    }
}
