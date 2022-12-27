@extends('common.tariff.settings.index')

@section('subtab')
    <form
        action="{{ route('tariff.settings.update', $community) }}"
        method="post"
        id="general_form_{{ $community->id }}"
        class="community-settings"
        enctype="multipart/form-data"
        data-tab="tariffPageSettingsMessages"
    >
        @csrf
        @if (env('USE_TRIAL_PERIOD', true))
        <!-- Пробный период -->
         <div>
            <div class="row align-items-center">
                <div class="col-sm-10 col-12">
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center">
                        <label class="trial_period pointer" for="trial_period">
                            {{ __('tariff.trial_period') }}
                        </label>

                        <div class="w-25 ms-0 ms-sm-2 mt-1 mt-sm-0">
                            <select
                                class="form-select pointer "
                                id="trial_period"
                                name="trial_period"
                            >
                                @if ($community->tariff !== null)
                                    <option value="0" @if ($community->tariff->test_period == 0) selected @endif>{{ __('base.none') }}</option>
                                    <option value="1" @if ($community->tariff->test_period == 1) selected @endif>1 {{ __('base.day_low') }}</option>
                                    <option value="3" @if ($community->tariff->test_period == 3) selected @endif>3 {{ __('base.days_rus_low') }}</option>
                                    <option value="7" @if ($community->tariff->test_period == 7) selected @endif>7 {{ __('base.days_low') }}</option>
                                    <option value="14" @if ($community->tariff->test_period == 14) selected @endif>14 {{ __('base.days_low') }}</option>
                                    <option value="30" @if ($community->tariff->test_period == 30) selected @endif>30 {{ __('base.days_low') }}</option>
                                    <option value="90" @if ($community->tariff->test_period == 90) selected @endif>90 {{ __('base.days_low') }}</option>
                                    <option value="180" @if ($community->tariff->test_period == 180) selected @endif>180 {{ __('base.days_low') }}</option>
                                    <option value="365" @if ($community->tariff->test_period == 365) selected @endif>365 {{ __('base.days_low') }}</option>
                                @endif
                            </select>

                            <span class="badge bg-warning hide" title="{{ __('base.unsaved_data') }}">
                                <i data-feather='save' class="font-medium-1" ></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <hr />
        </div>
        @endif

        <!-- Приветственное сообщение -->
        @include('common.tariff.assets.tariff_welcome')

        <!-- Сообщение благодарности за оплату тарифа -->
        @include('common.tariff.assets.tariff_success')

        <!-- Submit -->
        <div class="community-settings__buttons">
            <button
                class="button-filled button-filled--primary"
                type="submit"
                data-repeater-create
            >
                {{ __('base.save') }}
            </button>

            <a
                href="{{ url()->previous() }}"
                class="button-filled button-filled--primary-15"
            >
                Отменить
            </a>
        </div>
    </form>
@endsection
