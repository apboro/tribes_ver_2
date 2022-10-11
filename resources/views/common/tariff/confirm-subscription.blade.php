@extends('layouts.auth')

@section('content')
<div class="confirmation_subscription">
    <h2 class="confirmation_subscription__title">Подтверждение подписки</h2>
    <div class="confirmation_subscription__header">
        <div class="confirmation_subscription__header-col1">
            <img class="confirmation_subscription__avatar" src="/images/photo.png" alt="photo of subscriber">
            <div class="confirmation_subscription__header-col1--right">
                <h4 class="confirmation_subscription__header-col1-title">{{$community->title}}</h4>
                <p class="confirmation_subscription__name-value subscribers">
                    <span class="col1">Подписчиков:</span>
                    <span>300.5К</span>
                </p>
            </div>
        </div>

        <div class="confirmation_subscription__header-col2">
            <p class="confirmation_subscription__name-value">
                <span class="confirmation_subscription__name">Тарифный план:</span>
                <span class="confirmation_subscription__value">{{$tariff->title}}</span>
            </p>
            <p>
                <span class="confirmation_subscription__name">Стоимость:</span>
                <span class="confirmation_subscription__value">{{$tariff->price}} &#8381;</span>
            </p> 
        </div>

    </div>
    <div class="confirmation_subscription__body">
        <div class="confirmation_subscription__confirm-check">
            <input class="confirmation_subscription__confirm-check-input" id="all_rights_check" data-checked="false" type="checkbox">
            <label class="confirmation_subscription__confirm-check-label" for="all_rights_check">Подтверждаю ознакомление и согласие с <span class="text-primary">правилами</span></label>
        </div>
        <div class="confirmation_subscription__confirm-check-rights" id="confirm-check-rights">
            <div class="confirmation_subscription__confirm-check">
                <input class="confirmation_subscription__confirm-check-input" data-checked="false" type="checkbox">
                <a class="confirmation_subscription__btn-link" target="_blank">Правила пользования</a>
            </div>
            <div class="confirmation_subscription__confirm-check">
                <input class="confirmation_subscription__confirm-check-input" data-checked="false" type="checkbox">
                <a class="confirmation_subscription__btn-link" target="_blank">Политика обработки персональных данных</a>
            </div>
            <div class="confirmation_subscription__confirm-check">
                <input class="confirmation_subscription__confirm-check-input" data-checked="false" type="checkbox">
                <a class="confirmation_subscription__btn-link" target="_blank">Согласие на обработку персональных данных</a>
            </div>
            <div class="confirmation_subscription__confirm-check">
                <input class="confirmation_subscription__confirm-check-input" data-checked="false" type="checkbox">
                <a class="confirmation_subscription__btn-link" target="_blank">Согласие на получение информационной рассылки</a>
            </div>
            <div class="confirmation_subscription__confirm-check">
                <input class="confirmation_subscription__confirm-check-input" data-checked="false" type="checkbox">
                <a class="confirmation_subscription__btn-link" target="_blank">Согласие на подписку</a>
            </div>
            <div class="confirmation_subscription__confirm-check">
                <input class="confirmation_subscription__confirm-check-input" data-checked="false" type="checkbox">
                <a class="confirmation_subscription__btn-link" target="_blank">Агентский договор (публичная оферта)</a>
            </div>
        </div>
    </div>
    <div class="confirmation_subscription__footer">
        <div>
            <label class="confirmation_subscription__email-label" for="email">Email*</label>
            <input class="confirmation_subscription__email-input" id="email" placeholder="ivan@moyapochta.ru" name="email" required="true">
        </div>
        <a class="button-filled button-filled--primary" href="{{$community->getTariffPayLink(['amount' => $tariff->price,'currency' => 0,'type' => 'tariff'], $community)}}">Оплатить</a>
    </div>
</div>
@endsection