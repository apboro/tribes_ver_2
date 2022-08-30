<template>
    <div class="profile-community">
        <div class="profile-community__common-data">
            <div class="profile-community__avatar">
                <img src="/images/no-user-avatar.svg" alt="">
            </div>

            <p class="profile-community__community-title">
                Tech in UK
            </p>

            <p class="profile-community__community-link">
                https://t.me/techinuk
            </p>

            <span class="profile-community__community-badge">
                telegram
            </span>
        </div>

        <div class="profile-community__connect">
            <p class="profile-community__connect-label">
                Дата подключения:
            </p>

            <p class="profile-community__connect-date">
                12 july 2022
            </p>
        </div>

        <div class="profile-community__pay-link-block">
            <p class="profile-community__pay-link-label">
                Ссылка на страницу оплаты для доступа к сообществу
            </p>
            
            <div class="profile-community__pay-link-wrapper">
                <a href="" class="link profile-community__pay-link">
                    Перейти
                </a>

                <button class="link profile-community__pay-link profile-community__pay-link--divider">
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
                    ₽ 20344,34 
                    <!-- <span class="profile-community__money-difference profile-community__money-difference--plus">+1900 ₽</span> -->
                </p>

                <button class="button-filled profile-community__money-btn">
                    Вывести
                </button>
            </div>

            <div>
                <p class="profile-community__money-label">
                    Всего заработано
                </p>

                <p class="profile-community__money-value">
                    ₽ 150332,43
                </p>

                <button class="button-filled profile-community__money-btn button-filled--disabled">
                    Все транзакции
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'CommunityProfile',

    }
</script>
