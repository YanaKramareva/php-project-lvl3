<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class UrlCheckController extends Controller
{
    public function store($id)
    {
        $url = DB::table('urls')->find($id);
            $response = Http::get($url->name);
            $document = new Document($response->body());
            $h1 = optional($document->first('h1'))->text();
            $title = optional($document->first('title'))->text();
            $description = optional($document->first('meta[name=description]'))->getAttribute('content');

            DB::table('url_checks')->insert(
                [
                'url_id' => $id,
                'created_at' => Carbon::now(),
                'status_code' => $response->status(),
                'h1' => $h1,
                'title' => $title,
                'description' => $description,
                'updated_at' => Carbon::now()
                ]
            );

        return redirect()
            ->route('urls.show', ['url' => $id])
            ->with('success', 'Страница успешно проверена');
    }
}
