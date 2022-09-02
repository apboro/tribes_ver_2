@extends('common.community.profile')

@section('tab')
    <section
        class="community-tab"
        data-tab="tariffPageAdd"
    >
        <div class="community-tab__header">
            <a
                href="{{ route('community.tariff.list', $community) }}"
                class="button-back community-tab__prev-page-btn"
            >
                <svg width="27" height="16" viewBox="0 0 27 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M26 9C26.5523 9 27 8.55228 27 8C27 7.44772 26.5523 7 26 7L26 9ZM0.292893 7.2929C-0.0976311 7.68342 -0.097631 8.31658 0.292893 8.70711L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928934C7.68054 0.538409 7.04738 0.53841 6.65685 0.928934L0.292893 7.2929ZM26 7L1 7L1 9L26 9L26 7Z" fill="#7367F0"></path></svg>
            </a>

            <p class="community-tab__prev-page-title">
                Тарифы
            </p>
            
            <h2 class="community-tab__title">
                {{ __('tariff.add_tariff') }}
            </h2>
        </div>

        <form
            action="{{ route('community.tariff.add', $community->id) }}"
            method="post"
            enctype="multipart/form-data"
            id="tariff_add_form"
            class="community-settings"
        >
            <!-- Название тарифа -->
            <div class="community-settings__change-tariff">
                <div class="community-settings__form-item">
                    <label
                        class="form-label-red"
                        for="tariff_name"
                    >
                        {{ __('tariff.tariff_name') }}
                    </label>

                    <input
                        type="text"
                        class="form-control-red @error('tariff_name') form-control-red--danger @enderror"
                        id="tariff_name"
                        name="tariff_name"
                        aria-describedby="tariff_name"
                        placeholder="{{ __('base.standart') }}"
                    >

                    @error('tariff_name')
                        <span class="form-message form-message--danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="community-settings__form-item community-settings__input-group">
                    <div class="community-settings__input-wrapper">
                        <label
                            class="form-label-red"
                            for="tariff_cost"
                        >
                            {{ __('base.price') }}
                        </label>

                        <input
                            type="number"
                            class="form-control-red"
                            id="tariff_cost"
                            aria-describedby="tariff_cost"
                            name="tariff_cost"
                            placeholder="100"
                        />
                    </div>

                    <div class="community-settings__input-wrapper">
                        <label
                            class="form-label-red"
                            for="tariff_pay_period"
                        >
                            {{ __('base.term_access_community') }}
                        </label>

                        <select
                            class="form-control-red "
                            id="tariff_pay_period"
                            name="tariff_pay_period"
                        >
                            @if(env('FOR_TESTER'))
                                <option value="0" checked>1 {{ __('base.minute_low') }}</option>
                                <option value="1" >1 {{ __('base.day_low') }}</option>
                            @else
                                <option value="1" checked>1 {{ __('base.day_low') }}</option>
                            @endif
                            <option value="7">7 {{ __('base.days_low') }}</option>
                            <option value="14">14 {{ __('base.days_low') }}</option>
                            <option value="30">30 {{ __('base.days_low') }} </option>
                            <option value="90">90 {{ __('base.days_low') }}</option>
                            <option value="180">180 {{ __('base.days_low') }}</option>
                            <option value="365">365 {{ __('base.days_low') }}</option>
                            <!-- <option value="set">Свое значение</option> -->
                        </select>
                    </div>
                </div>

                <div class="">
                    <label
                        class="form-label-red"
                        for="tariff_name"
                    >
                        {{ __('tariff.number_button') }}
                    </label>

                    <input
                        type="number"
                        class="form-control-red"
                        id="number_button"
                        name="number_button"
                        aria-describedby="number_button"
                        value=""
                        placeholder="{{ __('base.number') }}"
                    >
                </div>
            </div>

            <div class="toggle-switch community-settings__item">        
                <label class="toggle-switch__switcher">
                    <input type="hidden" name="tariff" value="0" />
                    
                    <input
                        type="checkbox"
                        id="tariff_active"
                        class="toggle-switch__input"
                        value="1"
                        name="tariff"
                    >
                    <span class="toggle-switch__slider"></span>
                </label>

                <label
                    for="tariff_active"
                    class="toggle-switch__label"
                >
                    {{ __('tariff.activate_tariff') }}
                </label>
            </div>
                
            <div class="community-settings__buttons">
                <button
                    type="submit"
                    class="button-filled button-filled--primary">
                    {{ __('base.save') }}
                </button>

                <a
                    href="{{ route('community.tariff.list', $community) }}"
                    class="button-filled button-filled--primary"
                >
                    {{ __('base.cancel') }}
                </a>
            </div>
        </form>
    </section>
@endsection
