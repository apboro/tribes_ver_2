@extends('layouts.app')

@section('content')
    <div class="content-wrapper container-xxl p-0">
        <!-- Breadcrumbs block -->
        <div class="content-header row align-items-center" id="bredacrumbs">
            <div class="col-8 col-sm-6 col-md-8 col-lg-8">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0 border-0">
                            {{ __('base.mediaProducts') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-2" data-plugin="CommunitiesPage">

            <div id="projects-list" class="row">
                @if (count($courses))
                    <!-- Cards -->
                    @foreach ($courses as $course)
                        @include('common.follower.assets.product_item')
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
                                                {{ __('base.mediaProducts_empty') }}
                                            </h5>
                                        </div>
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
