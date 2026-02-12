<?php

namespace Tests\Feature\Providers;

use Tests\TestCase;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class AppServiceProviderTest extends TestCase
{
    public function testItForcesHttpsInProductionEnvironment(): void
    {
        Config::set('app.env', 'production');

        URL::shouldReceive('forceScheme')
            ->once()
            ->with('https');

        $provider = new AppServiceProvider($this->app);
        $provider->boot();
    }

    public function testItDoesNotForceHttpsInNonProductionEnvironment(): void
    {
        Config::set('app.env', 'local');

        URL::shouldReceive('forceScheme')
            ->never();

        $provider = new AppServiceProvider($this->app);
        $provider->boot();
    }
}
