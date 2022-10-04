@extends('layouts.app-redezign')

@section('content')

    <div class="content-wrapper container">
        <!-- Breadcrumbs block -->
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0 border-0">
                            {{ __('base.profile') }}
                            {{route('profile.project.add')}}
                            {{route('profile.project.edit',['project'=>1])}}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nav block -->
        @include('common.project.assets.nav')

        <div class="tab-content">
            @yield('tab')
        </div>
    </div>
@endsection