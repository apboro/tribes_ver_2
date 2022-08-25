@extends('common.community.profile')

@section('tab')
    <section data-tab="tariffPageEdit">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-6 col-lg-4">
                        <h4 class="card-title">{{ __('tariff.change_tariff') }}</h4>
                    </div>

                    <a href="{{ route('community.tariff.list', $community) }}"
                        class="btn btn-outline-primary custom waves-effect"
                    >
                        <i data-feather="arrow-left" class="font-medium-1"></i>

                        <span class="align-middle d-sm-inline-block d-none">
                            {{ __('base.back') }}
                        </span>
                    </a>
                </div>

                <form
                    action="{{ route('community.tariff.edit', [$community->id, $variantId]) }}"
                    method="post"
                    enctype="multipart/form-data"
                    id="tariff_edit_form"
                >
                    <div class="card-body">
                        @foreach ($community->tariff->variants as $tariff)
                            @if ($tariff->id == $variantId)
                                <!-- Название тарифа -->
                                <div class="col-sm-7 col-md-10 col-lg-7 mb-1">
                                    <label
                                        class="form-label"
                                        for="tariff_name">
                                        {{ __('tariff.tariff_name') }}
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="tariff_name"
                                        name="tariff_name"
                                        aria-describedby="tariff_name"
                                        value="{{ $tariff->title ? $tariff->title : '' }}"
                                        placeholder="{{ __('base.standart') }}"
                                    >
                                </div>
                                @error('tariff_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <!-- Money -->
                                <div class="col-sm-7 col-md-10 col-lg-7 mb-1">
                                    <div class="d-flex justify-content-between">
                                        <label
                                            class="form-label"
                                            for="tariff_cost">
                                            {{ __('base.price') }}
                                        </label>

                                        <label class="form-label" for="tariff_pay_period">
                                            {{ __('base.term_access_community') }}
                                        </label>
                                    </div>

                                    <div class="input-group">
                                        <input
                                            type="number"
                                            class="form-control w-50"
                                            id="tariff_cost"
                                            aria-describedby="tariff_cost"
                                            name="tariff_cost"
                                            value="{{ $tariff->price ? $tariff->price : '' }}"
                                            placeholder="100"
                                        />

                                        {{-- <select
                                            class="form-select pointer"
                                            id="tariff_currency"
                                            name="tariff_currency"
                                            disabled
                                        >
                                            <option value="rub" checked>₽</option>
                                            <option value="dollar">$</option>
                                            <option value="euro">€</option>

                                        </select> --}}

                                        <select class="form-select pointer w-25" id="tariff_pay_period"
                                            name="tariff_pay_period">
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
                                        </select>
                                    </div>
                                </div>

                                <!-- Активировать тариф -->
                                <div class="d-flex align-items-center mb-1">
                                    <div class="form-check form-check-primary form-switch">
                                        <input type="hidden" name="tariff" value="0" />

                                        <input type="checkbox"
                                            class="form-check-input pointer"
                                            id="donate_item_check_6"
                                            value="1"
                                            name="tariff"
                                            @if($tariff->isActive) checked @endif
                                        />
                                    </div>

                                    <label class="ms-1">
                                        {{ __('tariff.activate_tariff') }}
                                    </label>
                                </div>

                                <div class="col-sm-7 col-md-10 col-lg-7 mb-1">
                                    <label
                                            class="form-label"
                                            for="tariff_name">
                                        {{ __('tariff.number_button') }}
                                    </label>

                                    <input
                                            type="number"
                                            class="form-control"
                                            id="number_button"
                                            name="number_button"
                                            aria-describedby="number_button"
                                            value="{{ $tariff->number_button ? $tariff->number_button : '' }}"
                                            placeholder="{{ __('base.number') }}"
                                    >
                                </div>
                                @error('number_button')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            @endif
                        @endforeach
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-6 col-md-5 col-xl-3">
                                <a
                                    href="{{ route('community.tariff.list', $community) }}"
                                    class="btn btn-outline-danger waves-effect w-100"
                                >
                                    <span class="align-middle">
                                        {{ __('base.cancel') }}
                                    </span>
                                </a>
                            </div>

                            <div class="col-sm-6 col-md-5 col-xl-3 mt-1 mt-sm-0">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-float waves-light w-100">
                                    {{ __('base.save') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
