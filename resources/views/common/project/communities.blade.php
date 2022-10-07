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
                            <!-- {{route('profile.project.add')}}
                            {{route('profile.project.edit',['project'=>1])}} -->
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

        <div class="all-communities">
            <!-- START список сообществ проекта -->
            <div id="profile_list" class="profile__list communities">
                @foreach($communities as $community)
                <label for="{{$community->id}}" class="profile__item-wrap">
                    <input type="hidden" value="{{$community->id}}" name="communities[]" multiple>
                    <input type="checkbox" value="{{$community->id}}" class="profile__input" id="{{$community->id}}" name="communitiesCheckbox" multiple>
                    <div class="profile__item">
                        <div class="profile__item-image">
                            <img class="profile__image" src="{{$community->image ?? '/images/no-image.png'}}" alt="Image Profile">
                        </div>
                        <div class="profile__item-text">
                            <p class="profile__channel">{{$community->title}}</p>
                            <div class="profile__messenger">
                                <img src="/images/icons/social/telegram.png">
                                <p class="profile__text">{{$community->connection->chat_type == "channel" ? 'Канал': 'Чат'}}</p>
                            </div>
                        </div>
                    </div>
                </label>
                @endforeach
            </div>
            <!-- END список сообществ проекта -->
            <div class="all-communities__create-project">
            
            @if(true)
                <a
                    class="button-filled button-filled--primary"
                    href="{{ route('community.add') }}"
                >
                    Создать сообщество
                </a>
            @else
                <a
                    class="button-filled button-filled--primary"
                    href="{{ route('author.profile') }}"
                >
                    Создать сообщество
                </a>
            @endif
            </div>
        </div>
    </div>
@endsection