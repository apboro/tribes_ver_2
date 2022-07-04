@extends('layouts.app')

@section('content')
    <div class="content-wrapper container-xxl p-0">
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

            <div class="content-header-right col-3">
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
            </div>
        </div>

        <!-- Create project block -->
        <div class=" col-12" data-plugin="CreateCommunityPage">
            <div class="card">
                <!-- TAB MAIN -->
                @include('common.community.assets.create_bot_main')

                <!-- TAB TELEGRAM-CHAT -->
                @include('common.community.assets.create_bot_telegram_channel')

                <!-- TAB TELEGRAM-GROUP -->
                @include('common.community.assets.create_bot_telegram_group')
            </div>
        </div>
    </div>
@endsection
