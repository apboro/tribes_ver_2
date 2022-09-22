@extends('layouts.app-redezign')

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

            <div class="community__profile-btn-wrapper">
                <button
                    id="btn_profile"
                    class="community__profile-btn @if(data::get('is_visible_sidebar') == 'false') active @endif"
                    onclick="CommunityPage.toggleProfileVisibility(event)"
                    data-switch-visibility-btn
                >
                    @if(data::get('is_visible_sidebar') == 'false') Открыть @else Скрыть @endif профиль
                </button>
            </div>
            
            @yield('tab')
        </div>
    </div>
@endsection
