<section class="profile" data-plugin="Profile">

    <div class="profile__selected-project-community d-none">
        <h5 class="selected-project">Show</h5>
        <h5 class="selected-community">Hide</h5>
    </div>
    <div class="profile__hide-show" data-tab="profileBlock">

        @if( empty($project) )
        <!--START нет проектов и сообществ -->
        <div class="profile__start-work">
            <div class="profile__community_not_selected">
                <p>У вас нет проектов. Начните работу с Tribes, создав новый проект и добавив в него свои сообщетсва.</p>
            </div>

            <button
                class="button-filled button-filled--primary"
                type="submit"
                data-repeater-create
            >
                Начать работу
            </button>
        </div>
        <!--END нет проектов и сообществ -->

        @else
        <div class="profile__wrapper">
            <div class="profile__channel-owner">
                @include('common.template.assets.project_list')

                <!-- START если сообщество не выбрано -->
                <div class="profile__community_not_selected">
                    <p>Данные выводятся по всем сообществам проекта, если хотите посмотреть тарифы конкретного сообщества, выберите его из списка.</p>
                </div>
                <!-- END если сообщество не выбрано -->


                <!-- START профиль выбранного сообщества -->
                <div class="profile__community_selected">
                    <div class="profile__selected-wrap">
                        <div class="profile__community-img">
                            <img src="/images/avatars/1.png" alt="Avatar">
                        </div>
                        <div class="profile__community-description">
                            <h4 class="profile__community-description--title">Канал Димы Коваля</h4>
                            <a class="profile__community-description--link" href="https://t.me/TribesSupport_bot">
                                <img src="/images/icons/social/telegram.png" alt="Telegram">
                                https://t.me/techinuk
                            </a>
                            <p class="profile__community-description--subscribers">
                                <h6 class="profile__community-description--subscribers-text">Подписчиков:</h6>
                                <p class="profile__community-description--subscribers-quantity">300K</p>
                            </p>
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
                <!-- END профиль выбранного сообщества -->
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

                <!-- START список сообществ проекта -->
                <div id="profile-list" class="profile__list" data-massive="{{ 5+5 }}">
                    @for($x = 0; $x < count($demoCommunities); $x++)
                    <div class="profile__item-wrap" id="community_{{ $x }}" >
                        <input type="radio" id="community-item_{{ $x }}" name="community-item" class="profile__input">
                        <label for="community-item_{{ $x }}" class="profile__item">
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
                        </label>
                    </div>
                    @endfor
                </div>
                <!-- END список сообществ проекта -->


                <!-- START если проекта еще нет, как начать создавать -->
                <div class="profile__community_not_selected full-width">
                    <p>Вы можете объединять сообщества в одном проекте. Проекты позволят вам лучше организовать свое рабочие пространство в Tribes, а также смотреть по проектам статистику, донаты и тарифы в общем контексте.
                    <br></br>
                    Чтобы создать проект, откройте меню «Профиль» 🠖 «Мои проректы».</p>
                </div>
                <!-- END если проекта еще нет, как начать создавать -->



                <!-- START если проект еще не создан -->
                <div class="profile__community_not_selected full-width">
                    <p>Вы можете объединять сообщества в одном проекте. Проекты позволят вам лучше оргазивать свое рабочие пространство в Tribes, а также смотреть по проектам статистику, донаты и тарифы в общем контексте.</p>
                </div>
                <!-- END если проект еще не создан -->


            </div>
            <div id="load_container"> </div>
        </div>
        @endif
    </div>

    <div class="community__profile-btn-wrapper project-community">
        <button
            id="btn_profile"
            class="community__profile-btn"
            onclick="Profile.toggleProfileCommunityVisibility(event)"
            data-switch-visibility-btn
        >
            Скрыть
        </button>
    </div>
</section>