@extends('layouts.app')

@section('content')
<div class="content-wrapper container-xxl p-0" data-plugin="LessonPage">
    <!-- Breadcrumbs block -->
    @include('common.lesson.assets.breadcrumbs')

    <div class="content-wrapper container-xxl p-0">
        <div class="content-body">
            <section class="app-user-view-account">
                <div class="row">
                    <!-- Profile Content -->
                    <div
                        class="col-xl-8 col-lg-7 col-md-7"
                        id="profile-content"
                    >
                        <!-- Profile nav -->
                        @include('common.lesson.assets.nav')
                        
                        <!-- Tabs -->
                        @yield('tab')
                    </div>
                    
                    <!-- Profile Sidebar -->                    
                    @include('common.lesson.assets.sidebar')
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
