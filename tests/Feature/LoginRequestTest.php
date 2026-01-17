<?php

namespace Tests\Feature;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    public function test_no_exception_when_rate_limit_not_exceeded(): void
    {
        RateLimiter::shouldReceive('tooManyAttempts')
            ->once()
            ->andReturn(false);

        $request = new LoginRequest();

        // Метод должен завершиться без исключения
        $request->ensureIsNotRateLimited();

        $this->assertTrue(true);
    }

    public function test_validation_exception_when_rate_limit_exceeded(): void
    {
        Event::fake();

        RateLimiter::shouldReceive('tooManyAttempts')
            ->once()
            ->andReturn(true);

        RateLimiter::shouldReceive('availableIn')
            ->once()
            ->andReturn(120); // 2 минуты

        $request = new LoginRequest();

        try {
            $request->ensureIsNotRateLimited();
            $this->fail('Expected ValidationException was not thrown');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('email', $e->errors());
            $this->assertStringContainsString('120', $e->errors()['email'][0]);
            $this->assertStringContainsString('2', $e->errors()['email'][0]);

            Event::assertDispatched(Lockout::class);
        }
    }

    public function test_lockout_event_is_dispatched(): void
    {
        Event::fake();

        RateLimiter::shouldReceive('tooManyAttempts')
            ->once()
            ->andReturn(true);

        RateLimiter::shouldReceive('availableIn')
            ->once()
            ->andReturn(60);

        $request = new LoginRequest();

        try {
            $request->ensureIsNotRateLimited();
        } catch (ValidationException $e) {
            // Игнорируем исключение, проверяем событие
        }

        Event::assertDispatched(Lockout::class);
    }
}
