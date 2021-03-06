@extends('layouts.app'))
@section('content')
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Сайты</h1>
        <table class="table table-bordered table-hover text-nowrap">
            <tr>
                <th>ID</th>
                <th>{{ __('messages.Name') }}</th>
                <th>{{ __('messages.Last check') }}</th>
                <th>{{ __('messages.Status code') }}</th>
            </tr>
            @if ($urls)
                @foreach($urls as $url)
                    <tr>
                        <td style="width: 5%">{{$url->id}}</td>
                        <td>
                            <a href="{{route('urls.show', $url->id)}}">{{ $url->name }}</a>
                        </td>
                        <td>{{ $lastChecks[$url->id]->created_at ?? ''}}</td>
                        <td>{{ $lastChecks[$url->id]->status_code ?? '' }}</td>
                    </tr>
                @endforeach
            @endif
        </table>
        <div class="pagination justify-content-end">
        {{ $urls->links('pagination::bootstrap-5') }}
        </div>
        </div>
@endsection

