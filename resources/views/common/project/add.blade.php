@extends('layouts.app')

@section('content')

<div class="analytics-community__analytics-wrap projects_creation">
    <div class="analytics-community__title-wrap">
        <a href="{{route('profile.project.list')}}" class="button-back">
            <svg width="27" height="16" viewBox="0 0 27 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M26 9C26.5523 9 27 8.55228 27 8C27 7.44772 26.5523 7 26 7L26 9ZM0.292893 7.2929C-0.0976311 7.68342 -0.097631 8.31658 0.292893 8.70711L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928934C7.68054 0.538409 7.04738 0.53841 6.65685 0.928934L0.292893 7.2929ZM26 7L1 7L1 9L26 9L26 7Z" fill="#7367F0"></path></svg>
        </a>

        <h2 class="content-header-title float-start mb-0 border-0">
            {{__('base.my_projects')}}
        </h2>
    </div>
    <div>
        <h2 class="analytics-community__title">Создание проекта</h2>
    </div>
</div>
   @csrf

    <!-- START создание проекта -->
    <div class="project-creation" data-plugin="Project" >
        <div class="project-creation__communities">
            <div class="project-creation__communities-main">
                <p class="project-creation__communities-another">Другие сообщества</p>
                <div class="project-creation__communities-list-another">
                
                    <!-- START список сообществ проекта НЕ ДИНАМИЧЕСКИЙ ПОКА -->
                    <div id="profile_list_another" class="profile__list" onchange="Project.qtyOfCheckedCommunities(this)">
                        @foreach($communities as $community)
                        <label for="{{$community->id}}" class="profile__item-wrap">
                            <input type="checkbox" value="{{$community->id}}" class="profile__input" id="{{$community->id}}" name="communities[]" multiple>
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
                    <!-- END список сообществ проекта НЕ ДИНАМИЧЕСКИЙ ПОКА -->

                </div>
            </div>
            <div class="project-creation__communities-footer">
                <p class="project-creation__project-communities">{{__('base.selected')}}: <span id="qty_another_projects" class="qty">0</span></p>
                <button type="submit" class="button-empty button-empty--primary" onclick="Project.moveSelectedCommunities(this)">{{__('base.add')}}</button>
            </div>
        </div>
        <div class="project-creation__form-wrap">
            <form 
                class="project-creation__project"
                action="{{ route('profile.project.add') }}"
                method="post"
                enctype="multipart/form-data"
                id="project_creation_total"
            >
                @csrf
                <div class="project-creation__project--top">
                    <div class="project-creation__project-name">
                        <label for="projects-name">{{ __('base.project_name') }}</label>
                        <input
                            type="text"
                            class="project-creation__create-name form-control-red @error('title') form-control-red--danger @enderror"
                            id="projects-name"
                            name="title"
                            value="{{$request->get('title')}}"
                            aria-describedby="projects-name"
                            placeholder="{{ __('base.enter_name') }}"
                        >
                        @error('projects-name')
                        <span class="form-message form-message--danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button class="button-filled button-filled--primary project-creation__save" type="submit">{{ __('base.save') }}</button>
                </div>
                <div class="project-creation__project--bottom">
                    <div class="project-creation__project-header">
                        <p class="project-creation__project-communities">{{__('base.communities_project')}}: <span id="qty_of_communities_in_project" class="qty">0</span></p>
                        <label><input onclick="Project.toggleAll(this)" type="checkbox" name="community" id="chk_all" class="chk-all">{{__('base.select_all')}}</label>
                    </div>
                    <div class="project-creation__project-main">
                        <p class="project-creation__project-main--empty">Здесь находится список сообществ проекта, выберите сообщества из общего списка (слева) и добавьте их в свой проект.</p>
                        <div class="project-creation__list-communities" onchange="Project.qtyOfCheckedCommunitiesInProject(this)">




                        <!-- START список сообществ проекта НЕ ДИНАМИЧЕСКИЙ ПОКА -->
                        <div id="profile_list" class="profile__list communities">

                        </div>
                        <!-- END список сообществ проекта НЕ ДИНАМИЧЕСКИЙ ПОКА -->

                        </div>
                    </div>
                </div>
            </form>
            <div class="project-creation__project-footer">
                <p class="project-creation__project-communities">{{__('base.selected_communities')}}: <span id="qty_checked_communities_in_project" class="qty">0</span></p>
                <button class="button-empty button-empty--primary" onclick="Project.deleteSelectedCommunitiesFromProject(this)">{{__('base.remove_from_project')}}</button>
            </div>
        </div>
    </div>
    <!-- END создание проекта -->
@endsection