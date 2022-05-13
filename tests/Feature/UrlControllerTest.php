<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{
    private array $data;
    private int $id;

    protected function setUp(): void
    {
        parent::setUp();

        $this->data = [
            'name' => 'https://mvideo.ru',
            'created_at' => Carbon::now(),
        ];

        $this->id = DB::table('urls')->insertGetId($this->data);
    }

    public function testIndex()
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }

    public function testStore()
    {
        $url = ['name' => 'https://mvideo.ru'];
        $response = $this->followingRedirects()->post(route('urls.store'), ['url' => $url]);
        $response->assertSessionHasNoErrors();
        $response->assertOk();
        $response->assertSeeText($url['name']);
        $this->assertDatabaseHas('urls', $url);
    }

    public function testShow()
    {
        $response = $this->get(route('urls.show', ['url' => $this->id]));
        $response->assertOk();
        $response->assertSeeText($this->data['name']);
    }
}
