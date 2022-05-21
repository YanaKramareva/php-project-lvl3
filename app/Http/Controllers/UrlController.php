<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UrlController extends Controller
{
    public function index()
    {
        $urls = DB::table('urls')->paginate(5);
        $lastChecks = DB::table('url_checks')
            ->orderBy('url_id')
            ->latest()
            ->distinct('url_id')
            ->get()
            ->keyBy('url_id');
        return view('urls.index', compact('urls', 'lastChecks'));
    }


    public function store(Request $request)
    {
        $this->validate(
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
            flash(__('messages.The page has been added successfully'))->success();
            return redirect()
                ->route('urls.show', ['url' => $urlId]);
        }
        flash(__('messages.The page has already been added'))->info();
            return redirect()
                ->route('urls.show', ['url' => $url->id]);
    }

    public function show(int $id)
    {
        $url = DB::table('urls')->find($id);

        abort_unless($url, 404, __('messages.Page not found'));

        $urlChecks = DB::table('url_checks')
            ->where('url_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('urls.show', compact('url', 'urlChecks'));
    }
}
