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
            <p>
                <span class="confirmation_subscription__name">Период:</span>
                <span class="confirmation_subscription__value">{{$tariff->period}} дней</span>
            </p>
        </div>

    </div>
    <div class="confirmation_subscription__body">
        <div class="checkbox confirmation_subscription__confirm-check">


        </div>
    </div>
    <form class="confirmation_subscription__footer"
          action="{{ route('community.tariff.trial_subscribe', $community)}}"
          method="post"
    >
        @csrf
        <div>
            <label class="confirmation_subscription__email-label" for="email">Email*</label>
            <input class="confirmation_subscription__email-input" id="email" @auth value={{auth()->user()->email}} @endauth placeholder="e-mail@gmail.com" name="email" required="true">
        </div>

        <button type="submit"
        id="submit_btn"
        class="button-filled button-filled--primary"
        >
        Подтвердить
    </button>
    </form>
</div>
    <span
        id="email_message"
        class="form-message form-message--danger hide"
    ></span>
</div>
@endsection