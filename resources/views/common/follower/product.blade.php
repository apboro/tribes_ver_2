@extends('layouts.app')

@section('content')
    <div class="content-wrapper container-xxl p-0">

        <div class="btn-group mobile_panel">
            <button type="button" class="btn open_panel_btn" id="open_panel_btn">
                <i data-feather="arrow-right" class="font-medium-1 panel_arrow_right" id="arrow_panel_right"></i>
                <i data-feather="arrow-left" class="font-medium-1 panel_arrow_left" id="arrow_panel_left"></i>
            </button>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="col-12">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-product waves-effect">
                        <i data-feather="arrow-left" class="font-medium-1"></i>

                        <span class="align-middle d-sm-inline-block d-none">
                            {{ __('base.back') }}
                        </span>
                    </a>
                </div>

                @include('common.follower.assets.media_panel')

            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="media-views__content-wrapper">
                            <div class="col-md-12 media-views__content media-content-text-formatting">
                                {!! $template !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
