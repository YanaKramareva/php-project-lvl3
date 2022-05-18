<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class UrlCheckController extends Controller
{
    public function store(int $id)
    {
        $url = DB::table('urls')->find($id);
        abort_unless($url, 404);

        try {
            $response = Http::get($url->name);
            $document = new Document($response->body());
            $h1 = optional($document->first('h1'))->text();
            $title = optional($document->first('title'))->text();
            $description = optional($document->first('meta[name=description]'))->getAttribute('content');

            DB::table('url_checks')->insert([
                'url_id' => $id,
                'created_at' => Carbon::now(),
                'status_code' => $response->status(),
                'h1' => $h1,
                'title' => $title,
                'description' => $description,
                ]);
            return redirect()
                ->route('urls.show', ['url' => $id])
                ->with('success', 'Страница успешно проверена');
        } catch (HttpClientException $exception) {
            return redirect()
                ->route('urls.show', ['url' => $id])
                ->with('error', $exception->getMessage());
        }
    }
}
