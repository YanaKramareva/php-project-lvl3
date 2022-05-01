@extends('layouts.app')

@section('content')
    @include('layouts.flash_msg')
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Сайт: {{ $url->name }}</h1>
        <table class="table table-bordered table-hover text-nowrap">
            <tr>
                <td style="width: 200px">ID</td>
                <th>{{ $url->id }}</th>
            </tr>
            <tr>
                <td>Название</td>
                <td>{{ $url->name }}</td>
            </tr>
            <tr>
                <td>Дата создания</td>
                <td>{{ $url->created_at }}</td>
            </tr>
            <tr>
                <td>Дата обновления</td>
                <td>{{ $url->updated_at }}</td>
            </tr>
        </table>
        <h2 class="mt-5 mb-3">Проверки</h2>
        {{ Form::open(['url' => route('urls.checks.store', [$url->id])]) }}
        {{ Form::submit('Запустить проверку', array('class' => 'btn btn-primary')) }}
        {{ Form::close() }}
        <table class="table table-bordered table-hover text-nowrap">
            <tr>
                <th>ID</th>
                <th>Код ответа</th>
                <th>h1</th>
                <th>title</th>
                <th>description</th>
                <th>Дата создания</th>
                <th>Статус проверки</th>
            </tr>
            @if ($urlChecks)
                @foreach ($urlChecks as $urlCheck)
                    <tr>
                        <td>{{ $urlCheck->id }}</td>
                        <td>{{ $urlCheck->status_code }}</td>
                        <td>{{ Str::limit($urlCheck->h1, 30) }}</td>
                        <td>{{ Str::limit($urlCheck->title, 30) }}</td>
                        <td>{{ Str::limit($urlCheck->description, 30) }}</td>
                        <td>{{ $urlCheck->created_at }}</td>
                        <td>{{ $urlCheck->status_code}}</td>
                    </tr>
                @endforeach
            @endif
        </table>
        <div class="row">
            <div class="col">

            </div>
        </div>
    </div>
@endsection
