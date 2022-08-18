@extends('common.author.profile')
@section('tab')
@php
    $ty = Auth::user()->telegramData();
@endphp
    @include('common.template.alert.form_info', ['message' => \Session::get('success'), 'errors' => $errors])

    <div class="col-12" data-plugin="CreateCommunityPage">
        <div class="row">
            <!-- Telegram Card -->
            @include('common.author.assets.telegram_card', ['ty' => $ty])

            <!-- Discord Card -->
            {{--@include('common.author.assets.discord_card')--}}
        </div>
        @if(!Auth::user()->hasTelegramAccount())
        <div class="card">
            <div class="card-body">
                <h5 class="mb-1 text-center">
                    {{ __('community.communities_add') }}
                </h5>
            
                <div class="mb-0 text-center">
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
        </div>
        @endif
    </div>
@endsection
