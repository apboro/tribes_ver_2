@extends('common.community.profile')

@section('tab')
    <section
        class="community-tab"
        data-tab="tariffPageAdd"
    >
        <div class="community-tab__header">
            <a
                href="{{ route('community.tariff.list', $community) }}"
                class="community-tab__prev-page-btn"
            >
                <i data-feather="arrow-left" class="font-medium-1"></i>
            </a>

            <p class="community-tab__prev-page-title">
                Тарифы
            </p>
            
            <h2 class="community-tab__title">
                {{ __('tariff.change_tariff') }}
            </h2>
        </div>

        <form
            action="{{ route('community.tariff.edit', [$community->id, $variantId]) }}"
            method="post"
            enctype="multipart/form-data"
            id="tariff_edit_form"
            class="community-settings"
        >
            @foreach ($community->tariff->variants as $tariff)
                @if ($tariff->id == $variantId)
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
                            value="{{ $tariff->title ? $tariff->title : '' }}"
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
                                value="{{ $tariff->price ? $tariff->price : '' }}"
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
                                    <option value="0" @if ($tariff->period === 0) selected @endif>1 {{ __('base.minute_low') }}</option>
                                @endif
                                <option value="1" @if ($tariff->period == 1) selected @endif>1 {{ __('base.day_low') }}</option>
                                <option value="3" @if ($tariff->period == 3) selected @endif>3 {{ __('base.days_rus_low') }}</option>
                                <option value="7" @if ($tariff->period == 7) selected @endif>7 {{ __('base.days_low') }}</option>
                                <option value="14" @if ($tariff->period == 14) selected @endif>14 {{ __('base.days_low') }}</option>
                                <option value="30" @if ($tariff->period == 30) selected @endif>30 {{ __('base.days_low') }}</option>
                                <option value="90" @if ($tariff->period == 90) selected @endif>90 {{ __('base.days_low') }}</option>
                                <option value="180" @if ($tariff->period == 180) selected @endif>180 {{ __('base.days_low') }}
                                </option>
                                <option value="365" @if ($tariff->period == 365) selected @endif>365 {{ __('base.days_low') }}
                                </option>
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
                            value="{{ $tariff->number_button ? $tariff->number_button : '' }}"
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
                            @if($tariff->isActive) checked @endif
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
                @endif
            @endforeach
                
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
