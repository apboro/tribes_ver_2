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
                @if($communities->isEmpty())
                <div class="profile__community_not_selected create-community">
                    <p>
                        У Вас не добавлено ни одного сообщества.
                        <br>
                        Для работы с аналитикой, разделами монетизации и для получения доступа к созданию проектов, 
                        добавьте одно или несколько сообществ.
                    </p>
                </div>
                @else
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
                            <div class="profile__messenger folder">
                                @if($community->project_id == null)
                                <p class="profile__text" style="color:#B5B4B8">Не занесено в проект</p>
                                @else
                                <img src="/images/icons/folder.png">
                                <p class="profile__text">{{$community->project->title}}</p>
                                @endif
                                <!-- <p class="profile__text">{{$community->project_id == null ? 'Не занесено в проект' : 'Название проекта'}}name of project</p> -->
                            </div>
                        </div>
                    </div>
                </label>
                @endforeach
                @endif
            </div>
            <!-- END список сообществ проекта -->

            <div class="all-communities__create-project">
            
            <!-- @if(true) -->
                <a
                    class="button-filled button-filled--primary"
                    href="{{ route('community.add') }}"
                >
                    Добавить сообщество
                </a>
            <!-- @else
                <a
                    class="button-filled button-filled--primary"
                    href="{{ route('author.profile') }}"
                >
                    Добавить сообщество
                </a>
            @endif -->
            </div>
        </div>
    </div>
@endsection