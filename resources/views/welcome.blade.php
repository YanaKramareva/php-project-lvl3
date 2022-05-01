@extends('layouts.app')
@csrf
@section('content')
    @include('layouts.flash_msg')

    @if ($errors->any())
        @if ($errors->url)
        <div class="alert alert-danger">{{ 'Некорректный URL' }}</div>
        @endif
    @endif

    <main class="flex-grow-1">
            <div class="container-lg mt-3">
                <div class="row">
                    <div class="col-12 col-md-10 col-lg-8 mx-auto border rounded-3 bg-light p-5">
                        <h1 class="display-3">{{ 'Анализатор страниц' }}</h1>
                        <p class="lead">{{ 'Бесплатно проверяйте сайты на SEO пригодность' }}</p>
                        {{ Form::open(['url' => route('urls.store'), 'class' => 'd-flex justify-content-center']) }}
                        {{ Form::text('url[name]', '', ['class' => 'form-control form-control-lg', 'placeholder' => 'https://www.example.com']) }}
                        {{ Form::submit('Проверить', ['class' => 'btn btn-lg btn-primary ms-3 px-5 text-uppercase']) }}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </main>
    @endsection
