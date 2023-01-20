@extends('layouts.auth')

@section('content')
<div class="content-wrapper">
    <div class="content-header row"></div>
    <div class="content-body">
        <div class="misc-wrapper">
            <div class="misc-inner p-2 p-sm-3">
                <div class="w-100 text-center">
                    <h2 class="mb-1">
                        {{ __('error.title_404') }} 🕵🏻‍♀️
                    </h2>

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <p class="mb-2">
                        {{ __('error.description_404') }} 😖
                    </p>

                    <a
                        class="btn btn-primary mb-2 btn-sm-block"
                        href="{{\App\Providers\RouteServiceProvider::HOME}}"
                    >
                        {{ __('base.to_home') }}
                    </a>

                    <img
                        class="img-fluid"
                        src="/images/pages/error.svg"
                        alt="Error page"
                    >
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
