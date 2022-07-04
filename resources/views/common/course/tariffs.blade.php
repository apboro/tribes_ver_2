@extends('common.course.edit')

@section('tab')
<div class="tab-pane" id="tariffs" aria-labelledby="tariffs_tab" role="tabpanel">
    <form action="">
        <div class="card">
            <div class="card-header">
                <div class="col-7 col-lg-8">
                    <h4 class="card-title">
                        Тарифы
                    </h4>
                </div>

                <div class="col-sm-4 col-lg-3 col-xl-2">
                    <!-- Submit -->
                    <button
                        class="btn w-100 btn-icon btn-success d-flex align-items-center justify-content-center"
                        type="submit"
                        data-repeater-create
                        value="true"
                        name="settingsUpdate"
                    >
                        <i data-feather="save" class="font-medium-1"></i>
                        <span class="d-none d-sm-inline-block ms-1">
                            {{ __('base.save') }}
                        </span>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <p class="mb-1">
                    Выберите вариант оплаты единоразово или ежемесячно, и заполните необходимые тарифы. При единоразовой оплате материал остается у покупателя навсегда, при ежемесячной - до истечения срока.
                </p>
                <div class="row">
                    <div class="col-sm-6 col-lg-4 mb-1">
                        <label class="form-label pointer" for="basicSelect">Вариант оплаты</label>
                        <select class="form-select pointer" id="basicSelect">
                            <option selected>Единоразово</option>
                            <option>Ежемесячно</option>
                        </select>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="row"> 
            @for($i = 0; $i < 3; $i++)
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <!-- Название тарифа -->
                        <div class="col-12 mb-1">
                            <label class="form-label" for="tariff_name">
                                {{ __('tariff.tariff_name') }}
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="tariff_name"
                                name="tariff[0][name]"
                                aria-describedby="tariff_name"
                                placeholder="{{ __('base.standart') }}"
                            >
                        </div>
                        @error('tariff_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="col-12 mb-1">
                            <!-- Money -->
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="tariff_cost">
                                    {{ __('base.price') }}
                                </label>

                                {{--<label class="form-label" for="tariff_pay_period">
                                    {{ __('base.payment_period') }}
                                </label>--}}
                            </div>

                            <div class="input-group">
                                <input
                                    type="number"
                                    class="form-control w-50"
                                    id="tariff_cost"
                                    aria-describedby="tariff_cost"
                                    name="tariff[0][cost]"
                                    placeholder="100"
                                />

                                {{-- 

                                <select
                                    class="form-select pointer"
                                    id="tariff_currency"
                                    name="tariff_currency"
                                    disabled
                                >
                                    <option value="rub" checked>₽</option>
                                    <option value="dollar">$</option>
                                    <option value="euro">€</option>
                                </select> 
                                            
                                <select
                                    class="form-select pointer w-25"
                                    id="tariff_pay_period"
                                    name="tariff_pay_period"
                                >
                                    <option value="1" checked>1 {{ __('base.day_low') }}</option>
                                    <option value="3">3 {{ __('base.days_rus_low') }}</option>
                                    <option value="7">7 {{ __('base.days_low') }}</option>
                                    <option value="14">14 {{ __('base.days_low') }}</option>
                                    <option value="30">30 {{ __('base.days_low') }} </option>
                                    <option value="90">90 {{ __('base.days_low') }}</option>
                                    <option value="180">180 {{ __('base.days_low') }}</option>
                                    <option value="365">365 {{ __('base.days_low') }}</option>
                                </select>--}}
                            </div>
                        </div>
                        
                        <!-- Активировать тариф -->
                        <div class="d-flex align-items-center">
                            <div class="form-check form-check-primary form-switch">
                                <input type="hidden" name="tariff[0][active]" value="0" />
                                
                                <input
                                    type="checkbox"
                                    class="form-check-input pointer"
                                    id="donate_item_check_6"
                                    value="1"
                                    name="tariff[0][active]"
                                />
                            </div>

                            <label class="ms-1"
                                for="donate_description_5">{{ __('tariff.activate_tariff') }}
                            </label>
                        </div>                                            
                    </div>

                    <div class="card-footer">
                        <p class="mb-1">Опции тарифа</p>
                        

                        <div class="input-group mb-1">
                            <span class="input-group-text btn-success pointer">
                                <i data-feather='check' class="font-medium-1"></i>
                            </span>

                            <input type="text" class="form-control" placeholder="Аудио" name="tariff[0][option][]">

                            <span class="input-group-text btn-danger pointer">
                                <i data-feather='x' class="font-medium-1"></i>
                            </span>
                        </div>

                        <div class="input-group mb-1">
                            <span class="input-group-text btn-secondary pointer">
                                <i data-feather='minus' class="font-medium-1"></i>
                            </span>

                            <input type="text" class="form-control" placeholder="Аудио" name="tariff[0][option][]">

                            <span class="input-group-text btn-danger pointer">
                                <i data-feather='x' class="font-medium-1"></i>
                            </span>
                        </div>

                        <div class="col-12"> 
                            <button
                                id="add_card_btn"
                                class="btn btn-outline-success waves-effect w-100 d-flex align-items-center justify-content-center"
                                onclick="PaymentsPage.paymentsCardListPage.addCard()"
                            >
                                <i data-feather='plus'></i>
                                <span class="ms-1 d-none d-sm-inline-block">
                                    Добавить опцию
                                </span>
                            </button>
                        </div>
                    </div>
                </div> 
            </div>
            @endfor
        </div>
    </form>
</div>
@endsection
