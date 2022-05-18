<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Tests\TestCase;
use Exception;

class UrlCheckControllerTest extends TestCase
{
    private array $data;

    protected function setUp(): void
    {
        parent::setUp();
        $this->data = [
            'name' => 'https://google.com',
            'created_at' => Carbon::now(),
        ];
    }

    public function testStore()
    {
        $id = DB::table('urls')->insertGetId($this->data);
        $pathToHtml = __DIR__ . '/../Fixtures/fake.html';
        $content = file_get_contents($pathToHtml);
        if ($content === false) {
            throw new Exception('Something wrong with fixtures file');
        }

        Http::fake([$this->data['name'] => Http::response($content, 200)]);

        $expectedData = [
            'url_id' => $id,
            'status_code' => 200,
            'h1' => 'header',
            'title' => 'example',
            'description' => 'description',
            'created_at' => Carbon::now()
        ];

        $response = $this->post(route('urls.checks.store', $id));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('url_checks', $expectedData);
    }
}
