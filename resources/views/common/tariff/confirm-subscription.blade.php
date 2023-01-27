@extends('layouts.auth')

@section('content')
<div
    class="confirmation_subscription"
    data-plugin="TariffConfirmation"
>
    <h2 class="confirmation_subscription__title">
        Подтверждение подписки
    </h2>
    
    <div class="confirmation_subscription__header">
        <div class="confirmation_subscription__header-col1">
            <img
                class="confirmation_subscription__avatar"
                src="/images/no-user-avatar.svg"
                alt="photo of subscriber"
            >
            
            <div class="confirmation_subscription__header-col1--right">
                <h4 class="confirmation_subscription__header-col1-title">{{$community->title}}</h4>
                <p class="confirmation_subscription__name-value subscribers">
                    <!-- <span class="col1">Подписчиков:</span>
                    <span>300.5К</span> -->
                </p>
            </div>
        </div>

        <div class="confirmation_subscription__header-col2">
            <p class="confirmation_subscription__name-value">
                <span class="confirmation_subscription__name">Тарифный план:</span>
                <span class="confirmation_subscription__value">{{$tariff->title}}</span>
            </p>
            @if ($tariff->price != 0)
            <p>
                <span class="confirmation_subscription__name">Стоимость:</span>
                <span class="confirmation_subscription__value">{{$tariff->price}} &#8381;</span>
            </p>
            @endif
        </div>

    </div>
    <div class="confirmation_subscription__body @if ($tariff->price == 0) hidden @endif">

        <div class="checkbox confirmation_subscription__confirm-check">
            <div class="checkbox__wrapper community-settings__personal_tariff">
                <input
                    type="checkbox"
                    id="all_rights_check"
                    class="checkbox__input"
                    data-checked="false"
                    onchange="TariffConfirmation.checkAllRights()"
                >
                <label for="all_rights_check" class="checkbox__decor"></label>
            </div>
            <label class="checkbox__label" for="all_rights_check">
                Подтверждаю ознакомление и согласие с <span class="text-primary">правилами</span>
            </label>
        </div>

        <div class="confirmation_subscription__confirm-check-rights" id="confirm-check-rights">
            <div class="checkbox confirmation_subscription__confirm-check">
                <div class="checkbox__wrapper community-settings__personal_tariff">
                    <input type="checkbox" id="terms" class="checkbox__input" data-checked="false">
                    <label for="terms" class="checkbox__decor"></label>
                </div>
                <a class="checkbox__label confirmation_subscription__btn-link" href="{{ route('terms.index') }}" target="_blank">Правила пользования</a>
            </div>

            <div class="checkbox confirmation_subscription__confirm-check">
                <div class="checkbox__wrapper community-settings__personal_tariff">
                    <input type="checkbox" id="privacy" class="checkbox__input" data-checked="false">
                    <label for="privacy" class="checkbox__decor"></label>
                </div>
                <a class="checkbox__label confirmation_subscription__btn-link" href="{{ route('privacy.index') }}" target="_blank">Политика обработки персональных данных</a>
            </div>

            <div class="checkbox confirmation_subscription__confirm-check">
                <div class="checkbox__wrapper community-settings__personal_tariff">
                    <input type="checkbox" id="privacy_accept" class="checkbox__input" data-checked="false">
                    <label for="privacy_accept" class="checkbox__decor"></label>
                </div>
                <a class="checkbox__label confirmation_subscription__btn-link" href="{{ route('privacy_accept.index') }}" target="_blank">Согласие на обработку персональных данных</a>
            </div>

            <div class="checkbox confirmation_subscription__confirm-check">
                <div class="checkbox__wrapper community-settings__personal_tariff">
                    <input type="checkbox" id="ad_accept" class="checkbox__input" data-checked="false">
                    <label for="ad_accept" class="checkbox__decor"></label>
                </div>
                <a class="checkbox__label confirmation_subscription__btn-link" href="{{ route('ad_accept.index') }}" target="_blank">Согласие на получение информационной рассылки</a>
            </div>

            <div class="checkbox confirmation_subscription__confirm-check">
                <div class="checkbox__wrapper community-settings__personal_tariff">
                    <input type="checkbox" id="sub_terms" class="checkbox__input" data-checked="false">
                    <label for="sub_terms" class="checkbox__decor"></label>
                </div>
                <a class="checkbox__label confirmation_subscription__btn-link" href="{{ route('sub_terms.index') }}" target="_blank">Согласие на подписку</a>
            </div>
            
            <div class="checkbox confirmation_subscription__confirm-check">
                <div class="checkbox__wrapper community-settings__personal_tariff">
                    <input type="checkbox" id="agency_contract" class="checkbox__input" data-checked="false">
                    <label for="agency_contract" class="checkbox__decor"></label>
                </div>
                <a class="checkbox__label confirmation_subscription__btn-link" href="{{ route('agency_contract.index') }}" target="_blank">Агентский договор (публичная оферта)</a>
            </div>
        </div>
    </div>
    <div class="confirmation_subscription__footer">
        <div>
            <label class="confirmation_subscription__email-label" for="email">Email*</label>
            <input class="confirmation_subscription__email-input" id="email" @auth value={{auth()->user()->email}} @endauth placeholder="ivan@moyapochta.ru" name="email" required="true">
        </div>
        <!-- <a class="button-filled button-filled--primary" href="{{$community->getTariffPayLink(['amount' => $tariff->price,'currency' => 0,'type' => 'tariff'], $community)}}">Оплатить</a> -->
        
        
        <button
        id="submit_btn"
        class="button-filled button-filled--primary @if ($tariff->price != 0) button-filled--disabled @endif"
        onclick="TariffConfirmation.openRightsModal({
            communityName: '{{ $community->title }}',
            communityTariff: '{{ $tariff->title }}',
            communityTariffID: '{{ $tariff->id }}',
            communityAmount: '{{ $tariff->price }}',
            url: `{{ $community->getTariffPayLink(['amount' => $tariff->price,'currency' => 0,'type' => 'tariff'], $community) }}`
        })"
        >
        @if ($tariff->price === 0) Подтвердить @else Оплатить @endif
    </button>
</div>
    <span
        id="email_message"
        class="form-message form-message--danger hide"
    ></span>
    <div id="error_msg" class="alert alert-warning text-center" role="alert"></div>

</div>
@endsection