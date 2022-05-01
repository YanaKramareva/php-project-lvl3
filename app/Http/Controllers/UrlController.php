<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UrlController extends Controller
{
    public function index()
    {
        $urls = DB::table('urls')->paginate();
        $lastChecks = DB::table('url_checks')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('urls.index', compact('urls', 'lastChecks'));
    }


    public function store(Request $request)
    {
        $validatedData =  $this->validate(
            $request,
            [
                'url.name' => 'required|max:255|url'
            ]
        );

        $url = DB::table('urls')->where('name', $validatedData['url']['name'])->first();

        if (is_null($url)) {
            $urlId = DB::table('urls')->insertGetId(
                [
                    'name' => $validatedData['url']['name'],
                    'created_at' => Carbon::now('Europe/Moscow'),
                    'updated_at' => Carbon::now('Europe/Moscow')
                ]
            );

            return redirect()
                ->route('urls.show', ['url' => $urlId])
                ->with('success', 'Страница успешно добавлена');
        } else {
            return redirect()
                ->route('urls.show', ['url' => $url->id])
                ->with('success', 'Страница уже существует');
        }
    }

    public function show(int $id)
    {
        $url = DB::table('urls')->find($id);
        $urlChecks = DB::table('url_checks')
            ->where('url_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('urls.show', compact('url', 'urlChecks'));
    }
}