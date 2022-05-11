<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Tests\TestCase;
use Exception;

class UrlCheckControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
        $data = [
            'name' => 'https://google.com',
            'created_at' => Carbon::now(),
        ];

        $this->id = DB::table('urls')->insertGetId($data);
        $pathToHtml = __DIR__ . '/../Fixtures/fake.html';
        $content = file_get_contents($pathToHtml);
        if ($content === false) {
            throw new Exception('File not found');
        }

        Http::fake([$data['name'] => Http::response($content, 200)]);

        $this->expectedData = [
            'url_id' => $this->id,
            'status_code' => 200,
            'h1' => 'header',
            'title' => 'example',
            'description' => 'description',
            'created_at' => Carbon::now()
        ];
    }

    public function testStore()
    {
        $response = $this->post(route('urls.checks.store', $this->id));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('url_checks', $this->expectedData);
    }
}
