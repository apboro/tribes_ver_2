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

        <a href="https://t.me/techinuk" class="profile-community__community-link">
            <svg height="16" viewBox="0 0 176 176" width="16" xmlns="http://www.w3.org/2000/svg">
                <g id="Layer_2" data-name="Layer 2">
                    <g id="_16.telegram" data-name="16.telegram">
                        <circle id="background" cx="88" cy="88" fill="#1c8adb" r="88"/>
                        <path id="icon" d="m135.94 45.5-1.82.66-98.78 35.59a3.31 3.31 0 0 0 .29 6.4l25.57 7 4.77 14 4.77 14a4.54 4.54 0 0 0 7.32 1.63l13.21-12.48 25.94 17.59c3.17 2.16 7.69.56 8.5-3l17.18-75.91c.84-3.76-3.12-6.85-6.95-5.48zm-12.61 16.85-44.63 36.48-2.1 1.72a2.27 2.27 0 0 0 -.84 1.48l-.47 3.88-1.29 10.9a.5.5 0 0 1 -1 .08l-3.63-10.89-3.75-11.15a2.26 2.26 0 0 1 1.08-2.67l46.44-26.62 8.74-5c1.27-.74 2.57.87 1.45 1.79z" fill="#fff"/>
                    </g>
                </g>
            </svg>
            <span class="profile-community__community-link-telegram">https://t.me/techinuk</span>
        </a>
        
        <!-- @if ($community->isTelegram())
        <span class="profile-community__community-badge">
            telegram
        </span>
        @else
        <span class="profile-community__community-badge">
            discord
        </span>
        @endif -->
    </div>

    <div class="profile-community__connect-subscribers">
        <div class="profile-community__connect">
            <p class="profile-community__connect-subscribers-label">
                Дата подключения:
            </p>

            <p class="profile-community__connect-date" id="create_date_title" data-date-format>
                {{ $community->created_at->format('m-d-y') }}
            </p>
        </div>
        <div class="profile-community__subscribers">
            <p class="profile-community__connect-subscribers-label">
                Подписчиков:
            </p>

            <p class="profile-community__subscribers-qty">
                {{count($community->followers)}}
            </p>
        </div>
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
            <a
                href="{{ route('community.tariff.payment', ['hash' => App\Helper\PseudoCrypt::hash($community->id, 8)]) }}"
                target="_blank"
                class="link profile-community__pay-link profile-community__pay-link--divider"
            >
                Редактировать
            </a>
        </div>
    </div>

    <div class="profile-community__block-money">

        <div class="profile-community__money-info" style="justify-content: flex-end">
            <!-- <div class="profile-community__money">
                <p class="profile-community__money-label">
                    Доступный баланс
                </p>

                <p class="profile-community__money-value">
                    &#8381; 20344,34 
                </p>
                <p class="profile-community__money-difference">+&#8381; 1900</p>
            </div> -->

            <button class="button-filled profile-community__money-btn">
                Вывести
            </button>
        </div>

        <div class="profile-community__money-info" style="justify-content: flex-end">
            <!-- <div class="profile-community__money">
                <p class="profile-community__money-label">
                    Всего заработано
                </p>

                <p class="profile-community__money-value">
                    &#8381; 150332,43
                </p>
            </div> -->

            <button class="button-filled profile-community__money-btn button-filled--disabled">
                Все транзакции
            </button>
        </div>
    </div>

</div>