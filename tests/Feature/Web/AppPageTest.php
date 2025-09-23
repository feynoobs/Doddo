<?php

namespace Tests\Feature\Web;

use Tests\TestCase;

class AppPageTest extends TestCase
{
    public function test_spa_app_route_returns_ok(): void
    {
        $this->get('/')->assertOk();
        $this->get('/some/path')->assertOk();
    }
}


