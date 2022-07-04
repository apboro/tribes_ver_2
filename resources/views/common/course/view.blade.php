@extends('layouts.auth')

@section('content')
    <div class="auth-inner my-2">
        <div class="card mb-0 overflow-hidden">



            <div class="card-title d-flex flex-column align-items-center">
                <img src="{{ $course->preview()->first()->url ?? null }}" alt=""
                     class="active-image__img w-100">
            </div>
            @if($isAuthor)
                Вы - автор
            @endif

            <div class="card-body d-flex flex-column align-items-center">
                <h2 class="card-text mb-2">
                    {{ $course->title }}

                </h2>
                <span>(Страница чтения курса)</span>

                @foreach($course->lessons()->get() as $index => $lesson)
                        <span> Урок № {{ $index + 1 }} ({{ $lesson->title }})</span>
                @endforeach

            </div>
        </div>
    </div>
@endsection
