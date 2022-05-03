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
                'name' => 'https://test.com',
                'created_at' => Carbon::now('Europe/Moscow'),
                'updated_at' => Carbon::now('Europe/Moscow')
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
        $data = ['name' => 'https://test.com'];
        $response = $this->followingRedirects()->post(route('urls.store'), ['url' => $data]);
        $response->assertSessionHasNoErrors();
        $response->assertOk();
        $response->assertSeeText($data['name']);

        $this->assertDatabaseHas('urls', $data);
    }

    public function testShow()
    {
        $name = 'https://test.com';
        $id = DB::table('urls')->insertGetId(['name' => $name]);
        $response = $this->get(route('urls.show', ['url' => $id]));
        $response->assertOk();
        $response->assertSeeText($name);
    }
}
