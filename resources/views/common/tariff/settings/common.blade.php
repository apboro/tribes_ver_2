@extends('common.tariff.settings.index')

@section('subtab')
    <form
        action="{{ route('tariff.settings.update', $community) }}"
        method="post"
        id="general_form_{{ $community->id }}"
        enctype="multipart/form-data"
        data-tab="tariffPageSettingsCommon"
    >
        <div class="card">
            <div class="card-body">
                @csrf
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

                <div class="inactive-form-items">
                    <div class="row align-items-center">
                        <div class="col-md-12 col-12">
                            <div class="d-flex align-items-center">
                                <div class="form-check form-check-primary form-switch">
                                    <input type="checkbox" 
                                        class="form-check-input pointer" 
                                        id="tariff_notification"
                                        value="true" 
                                        name="tariff_notification" 
                                        {{ ($community->tariff->tariff_notification == true) ? 'checked' : null }}
                                    />
                                </div>

                                <label class="ms-1 pointer" for="tariff_notification">
                                    {{ __('form.receive_notifications') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr />
                </div>

                <!-- Приветственное сообщение -->
                @include('common.tariff.assets.tariff_welcome')

                <!-- Сообщение о напоминании оплатить тариф -->
                @include('common.tariff.assets.tariff_reminder')

                <!-- Сообщение благодарности за оплату тарифа -->
                @include('common.tariff.assets.tariff_success')

                <!-- Сообщение благодарности за оплату тарифа -->
                @include('common.tariff.assets.tariff_publication')

                <!-- Отправить в сообщество -->
                <div class="mt-2">                                   
                    <div class="row d-flex align-items-center">
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <div class="form-check form-check-primary form-switch">
                                    <input
                                        type="checkbox"
                                        class="form-check-input pointer"
                                        id="tariff_item_check_6"
                                        value="true"
                                        name="send_to_community"
                                    />
                                </div>

                                <label class="ms-1 pointer" for="tariff_item_check_6">
                                    {{ __('form.send_to_community') }}
                                </label>
                            </div>
                        </div>      
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <!-- Submit -->
                <div class="col-sm-5 col-lg-4 col-xl-3">
                    <button
                        class="btn w-100 btn-icon btn-success d-flex align-items-center justify-content-center"
                        type="submit"
                        onclick="CommunityPage.tariffPageSettings.commonBlock.trialPeriodAttention(event, '{{$community->tariff->test_period}}')"
                        data-repeater-create
                    >
                        <i data-feather="save" class="font-medium-1"></i>
                        <span class="ms-1">
                            {{ __('base.save') }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
