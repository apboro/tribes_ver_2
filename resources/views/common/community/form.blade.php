@extends('layouts.app')

@section('content')
    <div class="content-wrapper container">
        <!-- Breadcrumbs block -->
        <div class="content-header row align-items-center">
            <div class="content-header-left col-9 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">
                            {{ __('base.communities') }}
                        </h2>
                        
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('community.list') }}">
                                        {{ __('base.communities') }}
                                    </a>
                                </li>

                                <li class="breadcrumb-item active">
                                    {{ __('base.add_community') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>







            <!-- <div class="content-header-right col-3">
                <div class="text-sm-end text-md-end text-end">
                    <div class="mb-1 breadcrumb-right">
                        <a
                            href="{{ route('community.list') }}"
                            class="btn btn-outline-primary custom waves-effect"
                        >
                            <i data-feather="arrow-left" class="font-medium-1"></i>
                            
                            <span class="align-middle d-sm-inline-block d-none">
                                {{ __('base.back') }}
                            </span>
                        </a>
                    </div>
                </div>
            </div> -->
        </div>

        <!-- Create project block -->
        <div class="" data-plugin="CreateCommunityPage">

            <div class="analytics-community__analytics-wrap projects_creation">
                <div class="analytics-community__title-wrap">
                    <a href="javascript:void(0)" class="button-back" id="backButton">
                        <svg width="27" height="16" viewBox="0 0 27 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M26 9C26.5523 9 27 8.55228 27 8C27 7.44772 26.5523 7 26 7L26 9ZM0.292893 7.2929C-0.0976311 7.68342 -0.097631 8.31658 0.292893 8.70711L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928934C7.68054 0.538409 7.04738 0.53841 6.65685 0.928934L0.292893 7.2929ZM26 7L1 7L1 9L26 9L26 7Z" fill="#7367F0"></path></svg>
                    </a>
                    <h2 class="analytics-community__title-link">
                        {{__('base.my_projects')}}
                    </h2>
                </div>
                <div>
                    <h2 class="analytics-community__title" id="addCommunityTitle">Добавление сообщества</h2>
                </div>
            </div>

            <!-- <div class="card"> -->
                <!-- TAB MAIN -->
                @include('common.community.assets.create_bot_main')

                <!-- TAB TELEGRAM-CHAT -->
                @include('common.community.assets.create_bot_telegram_channel')

                <!-- TAB TELEGRAM-GROUP -->
                @include('common.community.assets.create_bot_telegram_group')
            <!-- </div> -->
        </div>
    </div>
@endsection
