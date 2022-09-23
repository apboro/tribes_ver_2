<section class="profile" data-plugin="Profile">
    <div class="profile__wrapper">
        <div class="profile__channel-owner">
            @include('common.template.assets.project_list')
            <div class="profile__community_not_selected">
                <p>Данные выводятся по всем сообществам проекта, если хотите посмотреть тарифы конкретного сообщества, выберите его из списка.</p>
            </div>
            <div class="profile__community_selected">
                <div class="profile__selected-wrap">
                    <div class="profile__community-img">
                        <img src="/images/avatars/1.png" alt="">
                    </div>
                    <div>
                        <h4>Канал Димы Коваля</h4>
                        <a class="footer__telegram-link" href="https://t.me/TribesSupport_bot">
                            <img class="footer__telegram-img" src="/images/icons/social/telegram.png" alt="Telegram">
                            https://t.me/techinuk
                        </a>
                        <p><h6>Подписчиков:</h6><p>300K</p></p>
                    </div>
                </div>
                <div class="profile-community__pay-link-block">
                    <p class="profile-community__pay-link-label">
                        Ссылка на страницу оплаты для доступа к сообществу
                    </p>
                
                    <div class="profile-community__pay-link-wrapper">
                        <a
                            href="#"
                            target="_blank"
                            class="link profile-community__pay-link"
                        >
                            Перейти
                        </a>

                        <button
                            class="link profile-community__pay-link profile-community__pay-link--divider"
                            onclick=""
                        >
                            Скопировать
                        </button>
                        <a
                            href="#"
                            class="link profile-community__pay-link profile-community__pay-link--divider"
                        >
                            Редактировать
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @php
            $demoCommunities = [
                    ['messenger' => "Что то", 'text' => "Что то", 'image' => "/images/avatars/1.png"],
                    ['messenger' => "Что то1", 'text' => "Что то1", 'image' => "/images/avatars/2.png"],
                    ['messenger' => "Что то2", 'text' => "Что то2", 'image' => "/images/avatars/3.png"],
                    ['messenger' => "Что то3", 'text' => "Что то3", 'image' => "/images/avatars/4.png"],
                    ['messenger' => "Что то3", 'text' => "Что то3", 'image' => "/images/avatars/5.png"],
                    ['messenger' => "Что то3", 'text' => "Что то3", 'image' => "/images/avatars/6.png"],
                    ['messenger' => "Что то3", 'text' => "Что то3", 'image' => "/images/avatars/7.png"],
                    ['messenger' => "Что то3", 'text' => "Что то3", 'image' => "/images/avatars/8.png"],
                ];
        @endphp



        {{ $communtiy->id ?? null }}

        <div class="profile__list-channel">
            <h4 class="profile__list-title">Сообщества Проекта</h4>
            <div id="profile-list" class="profile__list" data-massive="{{ 5+5 }}">
                @for($x = 0; $x < count($demoCommunities); $x++)
                <div class="profile__item-wrap" id="community_{{ $x }}" >
                    <a href="#" class="profile__item">
                        <div class="profile__item-image">
                            <img class="profile__image" src="{{ $demoCommunities[$x]['image'] }}">
                        </div>
                        <div class="profile__item-text">
                            <p class="profile__channel">{{ $demoCommunities[$x]['text'] }}</p>
                            <div class="profile__messenger">
                                <img src="/images/icons/social/telegram.png">
                                <p class="profile__text">{{ $demoCommunities[$x]['messenger'] }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endfor
            </div>
        </div>
        <div id="load_container"> </div>
    </div>
</section>