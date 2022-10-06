@extends('layouts.app-redezign')

@section('content')

<div class="content-wrapper container">
    <!-- Breadcrumbs block -->
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="analytics-community__title">
                        {{ __('base.my_projects') }}
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

    <div class="page-projects">
        @foreach($projects as $eachProject)
        <div class="page-projects__folder-wrap">
            <a href="{{ route('profile.project.edit', $eachProject ) }}">
                <div class="page-projects__folder-top">
                    <div class="parallelogram"></div>
                    <div class="parallelogram pink"></div>
                </div>
                <div class="page-projects__folder">
                    <div class="page-projects__folder--top">
                        <p class="page-projects__folder-project">{{__('base.project')}}</p>
                        <h5 class="page-projects__folder-project-name">{{$eachProject->title}}</h5>
                    </div>
                    <div class="page-projects__folder--bottom">
                        <div class="page-projects__folder-images">
                        @foreach($eachProject->communities as $eachCommunity)
                            <div class="page-projects__folder-image">
                                <img src="{{$eachCommunity->image ?? '/images/no-image.png'}}" alt="Avatar">
                            </div>
                        @endforeach
                        </div>
                        <p class="page-projects__folder-communities-qty">{{__('base.communities_v')}}: {{$eachProject->communities->count()}}</p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    <div class="page-projects__create-project">
        <a href="{{ route('profile.project.add') }}" class="button-filled button-filled--primary">
            {{__('base.create_project')}}
        </a>
    </div>
</div>
@endsection