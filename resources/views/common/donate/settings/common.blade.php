@extends('common.community.profile')

@section('tab')
    @include('common.template.alert.form_info', ['message' => \Session::get('message'), 'errors' => $errors])

    <section
        class="community-tab"
        data-tab="donatePageSettings"
    >
        <div class="community-tab__header">
            <a
                href="{{ $donate ? route('community.donate.add', ['community' => $community, 'id' => $donate->id]) : route('community.donate.add', $community) }}"
                class="button-back community-tab__prev-page-btn"
            >
                <svg width="27" height="16" viewBox="0 0 27 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M26 9C26.5523 9 27 8.55228 27 8C27 7.44772 26.5523 7 26 7L26 9ZM0.292893 7.2929C-0.0976311 7.68342 -0.097631 8.31658 0.292893 8.70711L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928934C7.68054 0.538409 7.04738 0.53841 6.65685 0.928934L0.292893 7.2929ZM26 7L1 7L1 9L26 9L26 7Z" fill="#7367F0"></path></svg>
            </a>

            <p class="community-tab__prev-page-title">Создание доната</p>
            <h2 class="community-tab__title">Общие настройки донатов в сообществе</h2>
        </div>

        <form
            action="{{ route('community.donate.settings.update', ['community' => $community->id, 'id' => $donate ? $donate->id : null]) }}"
            method="post"
            class=""
            id="donate_settings_form_{{ $community->id }}"
            enctype="multipart/form-data"
            data-switcher-cotainer
        >
            @csrf
            <div class="donate-settings__auto-public community-settings__item">
                <div class="toggle-switch">        
                    <label class="toggle-switch__switcher">
                        <input
                            type="checkbox"
                            id="donate_auto_prompt"
                            class="toggle-switch__input"
                            value="false"
                            name="donate_auto_prompt"
                            data-donate-check-id="0"
                            onchange="CommunityPage.donatePageSettings.donateSwitcher.onChangeDonateItemCheck(this)"
                            {{ $donate && $donate->isAutoPrompt ? 'checked' : null }}
                        >
                        <span class="toggle-switch__slider"></span>
                    </label>
    
                    <label
                        for="donate_auto_prompt"
                        class="toggle-switch__label"
                    >
                        {{ __('donate.auto_publish') }}
                    </label>
                </div>

                <div
                    class="@if($donate) {{ !$donate->isAutoPrompt ? 'inactive-form-items' : null }} @else inactive-form-items @endif"
                    data-donate-item-id="0"
                >
                    <label
                        class="form-label-red"
                        for="auto_prompt_time"
                    >
                        {{ __('base.time') }}
                    </label>
                    
                    <input
                        type="time"
                        class="form-control-red @error('auto_prompt_time') form-control-red--danger @enderror donate-settings__time"
                        id="auto_prompt_time"
                        name="auto_prompt_time"
                        aria-describedby="auto_prompt_time"
                        value="{{ $donate ? $donate->getPromptTime() : null }}"
                    />
                </div>
            </div>

            <!-- Сообщение после отправки доната THANKS -->
            @include('common.donate.assets.donate_success')

            <!-- Submit -->
            <div class="community-settings__buttons">
                <button
                    class="button-filled button-filled--primary"
                    type="submit"
                    value="true"
                    name="settingsUpdate"
                    data-repeater-create
                >
                    {{ __('base.save') }}
                </button>

                <a
                    href="{{ $donate ? route('community.donate.add', ['community' => $community, 'id' => $donate->id]) : route('community.donate.add', $community) }}"
                    class="button-filled button-filled--primary-15"
                >
                    Отменить
                </a>
            </div>
        </form>
    </section>
@endsection
