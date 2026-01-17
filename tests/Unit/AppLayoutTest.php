<?php

namespace Tests\Unit;

use App\View\Components\AppLayout;
use Tests\TestCase;

class AppLayoutTest extends TestCase
{
    public function test_it_can_be_instantiated()
    {
        $component = new AppLayout();

        $this->assertInstanceOf(AppLayout::class, $component);
    }

    public function test_it_returns_correct_view()
    {
        $component = new AppLayout();
        $view = $component->render();

        $this->assertEquals('layouts.app', $view->name());
    }

    public function test_view_can_be_rendered()
    {
        $component = new AppLayout();
        $view = $component->render();
        $html = $view->render();

        $this->assertIsString($html);
        $this->assertNotEmpty($html);
    }
}
