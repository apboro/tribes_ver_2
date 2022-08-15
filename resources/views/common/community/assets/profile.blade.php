<div class="profile-community community__profile" data-tab="profileBlock">
    <div class="profile-community__common-data">
        <div class="profile-community__avatar">
            <img
                src="@if ($community->image !== null) {{ $community->image }} @else /images/no-image.svg @endif"
                alt="User avatar"
            >
        </div>
        
        <p class="profile-community__community-title">
            {{ $community->title }}
        </p>

        <p class="profile-community__community-link">
            https://t.me/techinuk
        </p>
        
        @if ($community->isTelegram())
        <span class="profile-community__community-badge">
            telegram
        </span>
        @else
        <span class="profile-community__community-badge">
            discord
        </span>
        @endif
    </div>

    <div class="profile-community__connect">
        <p class="profile-community__connect-label">
            Дата подключения:
        </p>

        <p class="profile-community__connect-date" id="create_date_title" data-date-format>
            {{ $community->created_at->format('m-d-y') }}
        </p>
    </div>

    <div class="profile-community__pay-link-block">
        <p class="profile-community__pay-link-label">
            Ссылка на страницу оплаты для доступа к сообществу
        </p>
        
        <div class="profile-community__pay-link-wrapper">
            <a
                href="{{ route('community.tariff.payment', ['hash' => App\Helper\PseudoCrypt::hash($community->id, 8)]) }}"
                target="_blank"
                class="link profile-community__pay-link"
            >
                Перейти
            </a>

            <button
                class="link profile-community__pay-link profile-community__pay-link--divider"
                onclick="copyText('{{ route('community.tariff.payment', ['hash' => App\Helper\PseudoCrypt::hash($community->id, 8)]) }}')"
            >
                Скопировать
            </button>
        </div>
    </div>
    
    <div class="profile-community__money">
        <div>
            <p class="profile-community__money-label">
                Доступный баланс
            </p>

            <p class="profile-community__money-value">
                ₽ 20344,34 <span class="profile-community__money-difference profile-community__money-difference--plus">+1900 ₽</span>
            </p>
        </div>

        <div>
            <p class="profile-community__money-label">
                Всего заработано
            </p>

            <p class="profile-community__money-value">
                ₽ 150332,43
            </p>
        </div>

        <button class="button-filled profile-community__money-btn">
            Вывести
        </button>

        <button class="button-filled profile-community__money-btn button-filled--disabled">
            Все транзакции
        </button>
    </div>
</div>