@extends('layouts.auth')

@section('content')
    <div class="auth-inner my-2">
        <div class="card mb-0 overflow-hidden">
            <div class="card-title d-flex flex-column align-items-center">
                <img src="{{ $course->preview()->first()->url ?? null }}" alt=""
                     class="active-image__img w-100">
            </div>

            <div class="card-body d-flex flex-column align-items-center">
                <h2 class="card-text mb-2">
                    {{ $course->thanks_text  }}
                </h2>

                @if ($course->isActive)
                    <a href="{{ $course->getProductWithLesson($course->lessons->first()->id ?? 0) }}"
                       type="btn" class="btn btn-primary mt-1 mb-1">
                         Приступить к просмотру
                    </a>
                @else
                <a href="{{ route('follower.products') }}"
                    type="btn" class="btn btn-primary mt-1 mb-1">
                     Перейти в мои покупки
                </a>
                @endif
            </div>
        </div>
    </div>
@endsection
