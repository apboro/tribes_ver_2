@extends('layouts.auth')

@section('content')
<div class="content-wrapper">
    <div class="content-header row"></div>
    <div class="content-body">
        <div class="misc-wrapper">
            <div class="misc-inner p-2 p-sm-3">
                <div class="w-100 text-center">
                    <h2 class="mb-1">
                        {{ __('error.title_500') }} 🚀
                    </h2>
                    
                    <p class="mb-3">
                        {{ __('error.description_500') }}!
                    </p>

                    <a
                        class="btn btn-primary mb-1 btn-sm-block"
                        href="/"
                    >
                        {{ __('base.to_home') }}
                    </a>
                    
                    <img
                        class="img-fluid"
                        src="/images/pages/coming-soon.svg"
                        alt="Coming soon page"
                    >
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
