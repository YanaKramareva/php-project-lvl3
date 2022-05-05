<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UrlController extends Controller
{
    public function index(): Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $urls = DB::table('urls')->paginate(5);
        $lastChecks = DB::table('url_checks')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('urls.index', compact('urls', 'lastChecks'));
    }


    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData =  $this->validate(
            $request,
            [
                'url.name' => 'required|max:255|active_url'
            ]
        );

        $parsedUrl = parse_url($request['url.name']);
        $normalizedUrl = strtolower("{$parsedUrl['scheme']}://{$parsedUrl['host']}");

        $url = DB::table('urls')->where('name', $normalizedUrl)->first();

        if (is_null($url)) {
            $urlId = DB::table('urls')->insertGetId(
                [
                    'name' => $normalizedUrl,
                    'created_at' => Carbon::now()
                ]
            );

            return redirect()
                ->route('urls.show', ['url' => $urlId])
                ->with('success', 'Страница успешно добавлена');
        }

        return redirect()
            ->route('urls.show', ['url' => $url->id])
            ->with('success', 'Страница уже существует');
    }

    public function show(int $id): Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $url = DB::table('urls')->find($id);
        $urlChecks = DB::table('url_checks')
            ->where('url_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('urls.show', compact('url', 'urlChecks'));
    }
}
