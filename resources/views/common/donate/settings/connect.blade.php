@extends('layouts.app-redezign')

@section('content')
    {{-- @include('common.template.alert.form_info', ['message' => \Session::get('message'), 'errors' => $errors]) --}}
    <div class="container" data-plugin="CommunityPage">
        <section
            class=""
            data-tab="donatePage"
        >
            <div class="community-tab__header">
                <a
                    href="{{ route('project.donates', array_filter([ 'project' => isset($activeProject)? $activeProject->id :(isset($activeCommunity)?'c':''), 'community'=> isset($activeCommunity)?$activeCommunity->id:''])) }}"
                    class="button-back community-tab__prev-page-btn"
                >
                    <svg width="27" height="16" viewBox="0 0 27 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M26 9C26.5523 9 27 8.55228 27 8C27 7.44772 26.5523 7 26 7L26 9ZM0.292893 7.2929C-0.0976311 7.68342 -0.097631 8.31658 0.292893 8.70711L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928934C7.68054 0.538409 7.04738 0.53841 6.65685 0.928934L0.292893 7.2929ZM26 7L1 7L1 9L26 9L26 7Z" fill="#7367F0"></path></svg>
                </a>

                <p class="community-tab__prev-page-title">Донаты</p>

                <h2 class="community-tab__title">Создание доната</h2>
            </div>

            <form
                action="{{ route('community.donate.update', ['community' => $community->id, 'id' => $donate ? $donate->id : NULL]) }}"
                method="post"
                class="donate-add"
                id="donate_form_{{ $community->id }}"
                enctype="multipart/form-data"
                data-switcher-cotainer
            >
                @csrf
                <div class="donate-add__head">
                    <div>
                        <label class="form-label-red" for="title">
                            Наименование доната *
                        </label>
                        
                        <input
                            type="text"
                            id="title"
                            class="form-control-red @error('title') form-control-red--danger @enderror"
                            name="title"
                            aria-describedby="title"
                            placeholder="Наименование доната"
                            value="{{ $donate && $donate->title ? $donate->title : old('title') }}"
                        >
                        
                        @error('title')
                            <span class="form-message form-message--danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="community-settings__inline-command list">
                        <span class="form-label-red">
                            Инлайн команда данных донатов
                        </span>
                        <a
                            class="community-settings__inline-link" 
                            onclick="copyText('{{ '@' . env('TELEGRAM_BOT_NAME') }} {{ $donate ? $donate->inline_link : 'Создастся при сохранении' }}')"
                        >
                            {{ '@' . env('TELEGRAM_BOT_NAME') }} {{ $donate ? $donate->inline_link : __('donate.created_on_save') }}
                        </a>
                    </div>

                    <a
                        href="{{ route('community.donate.settings', ['community' => $community->id, 'id' => $donate ? $donate->id : NULL]) }}"
                        class="button-filled button-filled--primary donate-add__settings-btn"
                    >
                        Общие настройки
                    </a>
                </div>

                <!-- Описание доната DESCRIPTION -->
                @include('common.donate.assets.donate_description')

                <!-- 1st donate -->
                @include('common.donate.assets.donate_variant',['index' => 0])

                <!-- 2nd donate -->
                @include('common.donate.assets.donate_variant',['index' => 1])

                <!-- 3rd donate -->
                @include('common.donate.assets.donate_variant',['index' => 2])

                <!-- 4th CUSTOM donate -->
                @include('common.donate.assets.donate_variant_special',['index' => 3])
                
                <div class="toggle-switch community-settings__item">        
                    <label class="toggle-switch__switcher">
                        <input
                            type="checkbox"
                            id="tariff_item_check_6"
                            class="toggle-switch__input"
                            value="true"
                            name="send_to_community"
                        >
                        <span class="toggle-switch__slider"></span>
                    </label>

                    <label
                        for="tariff_item_check_6"
                        class="toggle-switch__label"
                    >
                        {{ __('form.send_to_community') }}
                    </label>
                </div>

                <div class="community-settings__buttons">
                    <button
                        class="button-filled button-filled--primary"
                        type="submit"
                        data-repeater-create
                    >
                        Сохранить
                    </button>

                    <a
                        href="{{ route('project.donates', array_filter([ 'project' => isset($activeProject)? $activeProject->id :(isset($activeCommunity)?'c':''), 'community'=> isset($activeCommunity)?$activeCommunity->id:''])) }}"
                        class="button-filled button-filled--primary-15"
                    >
                        Отменить
                    </a>
                </div>
            </form>
        </section>
    </div>
@endsection
