<?php

namespace Tests\Unit;

use App\View\Components\AppLayout;
use Tests\TestCase;

class AppLayoutTest extends TestCase
{
    public function testItReturnsCorrectView()
    {
        $component = new AppLayout();
        $view = $component->render();

        $this->assertEquals('layouts.app', $view->name());
    }

    public function testViewCanBeRendered()
    {
        $component = new AppLayout();
        $view = $component->render();
        $html = $view->render();

        $this->assertNotEmpty($html);
    }
}
