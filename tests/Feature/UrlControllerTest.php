<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::table('urls')->insert(
            [
            'name' => 'https://eldorado.ru',
            'created_at' => Carbon::now(),
            ]
        );

        $this->name = 'https://mvideo.ru';
        $this->id = DB::table('urls')->insertGetId(
            [
                'name' => $this->name,
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
        $this->withoutMiddleware();
        $data = ['name' => 'https://eldorado.ru'];
        $response = $this->followingRedirects()->post(route('urls.store'), ['url' => $data]);
        $response->assertSessionHasNoErrors();
        $response->assertOk();
        $response->assertSeeText($data['name']);
        $this->assertDatabaseHas('urls', $data);
    }

    public function testShow()
    {
        $response = $this->get(route('urls.show', ['url' => $this->id]));
        $response->assertOk();
        $response->assertSeeText($this->name);
    }
}
