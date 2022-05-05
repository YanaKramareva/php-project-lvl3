<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UrlControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('urls')->insert(
            [
            'name' => 'https://eldorado.ru',
            'created_at' => Carbon::now(),
            ]
        );
    }

    public function testIndex()
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }

    public function testStore()
    {
        $data = ['name' => 'https://eldorado.ru'];
        $response = $this->followingRedirects()->post(route('urls.store'), ['url' => $data]);
        $response->assertSessionHasNoErrors();
        $response->assertOk();
        $response->assertSeeText($data['name']);

        $this->assertDatabaseHas('urls', $data);
    }

    public function testShow()
    {
        $name = 'https://mvideo.ru';
        $id = DB::table('urls')->insertGetId(
            [
            'name' => $name,
            'created_at' => Carbon::now(),
            ]
        );
        $response = $this->get(route('urls.show', ['url' => $id]));
        $response->assertOk();
        $response->assertSeeText($name);
    }
}
