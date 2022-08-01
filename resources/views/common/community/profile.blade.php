@extends('layouts.app')

@section('content')
    <div class="content-wrapper container-xxl p-0" data-plugin="CommunityPage">
        <!-- Breadcrumbs block -->
        {{--@include('common.community.assets.profile_breadcrumbs')--}}
        
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <section class="app-user-view-account">
                    <div class="row">
                        <!-- Profile Sidebar -->                    
                        @include('common.community.assets.profile_sidebar')
                        
                        <!-- Profile Content -->
                        <div
                            class="@if(data::get('is_visible_sidebar') == 'false') col-12 @else col-xl-8 col-lg-7 col-md-7 order-0 order-md-1 @endif"
                            id="profile-content"
                        >
                            <!-- Profile nav -->
                            @include('common.community.assets.profile_nav')

                            <!-- Tabs -->
                            @yield('tab')
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
