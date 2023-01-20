@extends('layouts.app')

@section('content')
    <div class="content-wrapper container-xxl p-0">
        <div class="col col-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="display-4">{{ __('feedback.title') }} </h1>
                    <hr class="mb-4">

                    <form
                            action="{{ route('feedback.save')}}"
                            method="post"
                            enctype="multipart/form-data"
                            id="feedback_form"
                            class="community-settings"
                    >
                        @csrf
                        <label
                                class="form-label-red"
                                for="fb_email"
                        >
                            {{ __('base.email') }}
                        </label>
                        <input
                                type="email"
                                class="form-control-red"
                                id="fb_email"
                                required
                                name="fb_email"
                                placeholder="{{ __('base.email') }}"
                                value="{{$user->email}}"
                        >
                        <label
                                class="form-label-red"
                                for="fb_phone"
                        >
                            {{ __('base.phone') }}
                        </label>
                        <input
                                type="tel"
                                class="form-control-red"
                                id="fb_phone"
                                name="fb_phone"
                                placeholder="{{ __('base.phone') }}"
                                value="{{$user->phone}}"
                        >
                        <label 
                                class="form-label-red"
                                for="fb_name"
                        >
                            {{ __('base.name') }}
                        </label>
                        <input
                                type="text"
                                class="form-control-red"
                                id="fb_name"
                                name="fb_name"
                                placeholder="{{ __('base.name') }}"
                                value="{{$user->name}}"
                        >

                        <label
                                class="form-label-red"
                                for="fb_message"
                        >
                            {{ __('base.message') }}
                        </label>
                        <textarea

                                class="form-control-red"
                                required
                                id="fb_message"
                                name="fb_message"
                                placeholder="{{ __('base.message') }}"
                        ></textarea>

                        <div class="community-settings__buttons">
                            <button
                                    type="submit"
                                    class="button-filled button-filled--primary">
                                {{ __('base.send') }}
                            </button>

                            <a
                                    href="{{ URL::previous() }}"
                                    class="button-filled button-filled--primary-15"
                            >
                                {{ __('base.cancel') }}
                            </a>
                        </div>
                        <br>
                        @if (session()->has('success'))
                            <div class="col-sm-12">
                                <div class="alert  alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            </div>
                    @endif

                </div>  
            </div>
        </div>
    </div>
@endsection
