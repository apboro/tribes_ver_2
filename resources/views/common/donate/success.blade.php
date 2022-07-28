@extends('layouts.auth')

@section('content')
    <div class="auth-inner my-2">
        <div class="card mb-0 overflow-hidden">
            <div class="card-title d-flex flex-column align-items-center">
                @if ($payment->type == 'donate')
                    @if ($payment->payable()->first()->donate()->first()->getSuccessImage())
                        <img src="{{ $payment->payable()->first()->donate()->first()->getSuccessImage()->url }}" alt=""
                            class="active-image__img w-100">
                    @else
                        <img src="/images/chuck.jpg" alt=""
                            class="active-image__img w-100">
                    @endif
                @endif
                @if ($payment->type == 'tariff')
                    @if ($payment->payable()->first()->tariff()->first()->getThanksImage())
                        <img src="{{ $payment->payable()->first()->tariff()->first()->getThanksImage()->url }}" alt=""
                            class="active-image__img w-100">
                    @else
                        <img src="/images/chuck.jpg" alt=""
                            class="active-image__img w-100">
                    @endif
                @endif
            </div>

            <div class="card-body d-flex flex-column align-items-center">
                <h2 class="card-text mb-2">
                    @if ($payment->type == 'donate')
                        {{ $payment->payable()->first()->donate()->first()->success_description ? $payment->payable()->first()->donate()->first()->success_description : __('donate.thanks_for_donation') }}
                    @endif
                    @if ($payment->type == 'tariff')
                        {{ $payment->payable()->first()->tariff()->first()->thanks_description ? $payment->payable()->first()->tariff()->first()->thanks_description : 'Спасибо за оплату, тариф успешно активирован! Для подключения к сообществу нажмите кнопку "в телеграм", запустите диалог с Tribesbot и перейдите по пригласительной ссылке "подписаться".'}}
                    @endif
                </h2>

                <a href="https://t.me/{{ env('TELEGRAM_BOT_NAME') }}?start={{ App\Helper\PseudoCrypt::hash($payment->id) }}"
                    type="btn" class="btn btn-primary mt-1 mb-1">
                    {{ __('base.go_telegram') }}
                </a>
            </div>
        </div>
    </div>
@endsection
