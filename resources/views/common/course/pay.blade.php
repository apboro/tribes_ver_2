@extends('layouts.auth')

@section('og')
    
    
    <title>{{ $course->title }} | Spodial</title>
    <meta property="og:title" content="
        <?php
            function reduction($text, $length) {
                $lt = mb_strlen($text);
                if ($lt <= $length) return $text;
                $repl = preg_replace('/^(.{0,'.$length.'})\b\W.*$/u', '\\1', $text);
                $lr = mb_strlen($repl);
                if ($lr > $length) {
                    $repl = mb_substr($repl, 0, $length) . '...';
                } else if ($lr < $lt) {
                    $repl .= '...';
                }
                return $repl;
            }
            echo reduction($course->title, 120);
        ?>
    ">
    <meta name="description" content="{{ strip_tags($course->payment_description) }}">
    <meta property="og:type" content="{{ $course->title }}">
    <meta property="og:description" content="{{ strip_tags($course->payment_description) }}">
    <meta property="og:url" content="{{ $course->paymentLink() }}">
    <meta property="og:image" content="@if ($course->preview()->first()) {{ENV('APP_URL')}}{{ $course->preview()->first()->url }}@endif">
    <link rel="canonical" href="{{ $course->paymentLink() }}">       
@endsection

@section('content')
    <div class="auth-inner my-2" data-plugin="MeidaBuyPage">
        <div class="card mb-0" style="width: 400px;margin: 0 auto;">
            <div style="text-align: center" class="col-12">
{{--                @dd($course->preview()->first());--}}
                <img style="max-width:400px" src="@if ($course->preview()->first()){{ $course->preview()->first()->url }}@endif" alt="" class="w-100">
            </div>

            <form method="post" action="{{ $course->payLink(['course' => $course ]) }}" class="card-body  flex-column align-items-center">
                @csrf
                <div class="col-12">
                    <h2 class="card-title text-center">
                        {{ $course->payment_title }}
                    </h2>
                </div>
                <div class="col-12">
                    {!! $course->payment_description !!}
                </div>
                
                <hr>
                
                <div class="col-12">

                    <label for="email" class="form-label">Email*</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        class="form-control @error('email') error @enderror"
                        placeholder="john@example.com"
                        value="@auth {{ Auth::user()->email }} @endauth"
                    >
                        @error('email')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    <button type="submit" class="btn btn-outline-success waves-effect mt-1 w-100">
                        {{ $course->cost > 0 ? 'Купить — ' . $course->cost . '₽' : 'Получить бесплатно'}}
                    </button>
                </div>

                <div class="col-12 mt-1">
                    <span>
                        Покупая курс вы соглашаетесь с
                        <a href="{{ route('terms.index') }}" target="_blank" class="btn-link">правилами пользования</a>
                         и
                        <a href="{{ route('privacy.index') }}" target="_blank" class="btn-link">политикой конфиденциальности.</a>
                    </span>
                </div>
            </form>
        </div>
    </div>
@endsection
