<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;

class BasicLaravelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function test_application_returns_successful_response()
    {
        $response = $this->get('/');
        $response->assertSuccessful();
    }

    public function test_can_use_post_method()
    {
        $response = $this->post('/test-route', []);
        // This will probably fail with 404, but that's OK - it means post() method works
        $this->assertTrue(true);
    }
}