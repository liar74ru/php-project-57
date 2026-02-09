<?php

namespace Tests\Feature\Providers;

use Tests\TestCase;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class AppServiceProviderTest extends TestCase
{
    public function testAppServiceProviderCanBeInstantiated(): void
    {
        $provider = new AppServiceProvider($this->app);
        $this->assertInstanceOf(AppServiceProvider::class, $provider);
    }

    public function testAppServiceProviderHasBootMethod(): void
    {
        $provider = new AppServiceProvider($this->app);
        $this->assertTrue(method_exists($provider, 'boot'));

        // Вызываем метод
        $provider->boot();

        $this->assertTrue(true);
    }

    public function testAppServiceProviderHasRegisterMethod(): void
    {
        $provider = new AppServiceProvider($this->app);
        $this->assertTrue(method_exists($provider, 'register'));

        // Вызываем метод
        $provider->register();

        $this->assertTrue(true);
    }

    public function testItForcesHttpsInProductionEnvironment(): void
    {
        // Сохраняем оригинальное значение
        $originalEnv = $_ENV['APP_ENV'] ?? null;

        // Устанавливаем production окружение
        $_ENV['APP_ENV'] = 'production';
        putenv('APP_ENV=production');

        // Мокаем URL facade чтобы проверить вызов
        URL::shouldReceive('forceScheme')
            ->once()
            ->with('https');

        // Создаем и запускаем провайдер
        $provider = new AppServiceProvider($this->app);
        $provider->boot();

        // Восстанавливаем окружение
        if ($originalEnv !== null) {
            $_ENV['APP_ENV'] = $originalEnv;
            putenv("APP_ENV=$originalEnv");
        } else {
            unset($_ENV['APP_ENV']);
            putenv('APP_ENV');
        }
    }

    public function testItDoesNotForceHttpsIinNonProductionEnvironment(): void
    {
        // Устанавливаем не-production окружение
        $originalEnv = $_ENV['APP_ENV'] ?? null;
        $_ENV['APP_ENV'] = 'local';
        putenv('APP_ENV=local');

        // Ожидаем, что forceScheme НЕ будет вызван
        URL::shouldReceive('forceScheme')
            ->never();

        // Создаем и запускаем провайдер
        $provider = new AppServiceProvider($this->app);
        $provider->boot();

        // Восстанавливаем
        if ($originalEnv !== null) {
            $_ENV['APP_ENV'] = $originalEnv;
            putenv("APP_ENV=$originalEnv");
        } else {
            unset($_ENV['APP_ENV']);
            putenv('APP_ENV');
        }
    }
}
