<?php

namespace Tests\Feature;

use Tests\TestCase;

class WelcomeTest extends TestCase
{
    public function testHome(): void
    {
        $response = $this->get(route('welcome'));
        $response->assertOk();
    }
}
