@extends('layouts.app')

@section('content')
    <div class="content-wrapper container-xxl p-0">
        <!-- Breadcrumbs block -->
        <div class="content-header row align-items-center" id="bredacrumbs">
            <div class="col-8 col-sm-6 col-md-8 col-lg-8">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0 border-0">
                            {{ __('base.communities') }}
                        </h2>
                    </div>
                </div>
            </div>

            @if(Auth::user()->hasTelegramAccount())
            <div class="col-4 col-sm-6 col-md-4 col-lg-4">
                <div class="text-end mb-0">
                    <a
                        type="submit"
                        class="btn btn-success text-white"
                        href="{{ route('community.add') }}"
                    >
                        <i data-feather='plus' class="font-medium-1"></i>
                        <span class="d-none d-sm-inline-block ms-1">{{ __('base.add_community') }}</span>
                    </a>
                </div>
            </div>
            @endif
        </div>

        <div class="mt-2" data-plugin="CommunitiesPage">            
            
            <div id="projects-list" class="row">
                @if(count($communities))
                    <!-- Cards -->
                    @foreach($communities as $community)
                        @include('common.community.assets.community_item')
                    @endforeach
                @else
                    <!-- Empty list -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-8 col-12">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="text-center">
                                                {{ __('community.communities_empty') }}
                                            </h5>
                                        </div>
                                        
                                        @if(Auth::user()->hasTelegramAccount())
                                            <a
                                                class="btn btn-success text-white mt-1"
                                                href="{{ route('community.add') }}"
                                            >
                                                <i data-feather='plus' class="font-medium-1"></i>
                                                <span>{{ __('base.add_community') }}</span>
                                            </a>
                                        @else
                                            <a
                                                class="btn btn-success text-white mt-1"
                                                href="{{ route('author.profile') }}"
                                            >
                                                <i data-feather='arrow-right' class="font-medium-1"></i>
                                                <span>{{ __('base.get_started') }}</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- Tabs end -->
    </div>
@endsection
