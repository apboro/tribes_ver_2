@extends('layouts.app')

@section('content')
    <div class="community" data-plugin="CommunityPage">
        <div class="container">
            <!-- HEAD -->
            <header class="community__header">
                <h2
                    class="community__title"
                    title="{{ $community->title }}"
                >
                    {{ $community->title }}
                </h2>

                @include('common.community.assets.nav')
            </header>


            @include('common.community.assets.profile')
            
            @yield('tab')
        </div>
    </div>
@endsection
