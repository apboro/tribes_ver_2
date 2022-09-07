@extends('layouts.auth')

@section('content')
<div class="confirmation_subscription">
    <h2 class="confirmation_subscription__title">Подтверждение подписки</h2>
    <div class="confirmation_subscription__header">
        <div class="confirmation_subscription__header-col1">
            <img class="confirmation_subscription__avatar" src="/images/photo.png" alt="photo of subscriber">
            <div class="confirmation_subscription__header-col1--right">
                <h4 class="confirmation_subscription__header-col1-title">Tech in UK</h4>
                <p class="confirmation_subscription__name-value">
                    <span class="confirmation_subscription__name col1">Подписчиков:</span>
                    <span class="confirmation_subscription__subscribers">300.5К</span>
                </p>
            </div>
        </div>

        <div class="confirmation_subscription__header-col2">
            <p class="confirmation_subscription__name-value">
                <span class="confirmation_subscription__name">Тарифный план:</span>
                <span class="confirmation_subscription__value">Стандарт</span>
            </p>
            <p>
                <span class="confirmation_subscription__name">Стоимость:</span>
                <span class="confirmation_subscription__value">100 &#8381;</span>
            </p> 
        </div>

    </div>
    <div class="confirmation_subscription__body"></div>
    <div class="confirmation_subscription__footer"></div>
</div>
@endsection