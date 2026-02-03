<?php

namespace Tests\Feature\Middleware;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Http\Middleware\SetLocale;

class SetLocaleTest extends TestCase
{
    public function testItSetsLocaleFromSession()
    {
        // Arrange
        $middleware = new SetLocale();
        $request = new Request();

        // Устанавливаем русскую локаль в сессии
        Session::put('locale', 'ru');

        // Act - выполняем middleware
        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        // Assert - проверяем, что локаль установлена
        $this->assertEquals('ru', App::getLocale());
    }

    public function testItSetsLocaleFromBrowserHeader()
    {
        // Arrange
        $middleware = new SetLocale();

        // Запрос с русским языком в заголовке
        $request = Request::create('/', 'GET', [], [], [], [
            'HTTP_ACCEPT_LANGUAGE' => 'ru-RU,ru;q=0.9'
        ]);

        // Act
        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        // Assert
        $this->assertEquals('ru', App::getLocale());
    }
}
