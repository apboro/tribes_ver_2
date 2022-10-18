<div class="hidden" data-tab="telegram_group">
    <div class="analytics-community__analytics-wrap projects_creation">
        <div class="analytics-community__title-wrap">
            <button
                data-tab-btn="main"
                class="button-back" id="backButton"
                onclick="CreateCommunityPage.createCommunityBot.onClickTab(this);
                    CreateCommunityPage.createCommunityBot.stopSetInterval()"
            >
                <svg width="27" height="16" viewBox="0 0 27 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M26 9C26.5523 9 27 8.55228 27 8C27 7.44772 26.5523 7 26 7L26 9ZM0.292893 7.2929C-0.0976311 7.68342 -0.097631 8.31658 0.292893 8.70711L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928934C7.68054 0.538409 7.04738 0.53841 6.65685 0.928934L0.292893 7.2929ZM26 7L1 7L1 9L26 9L26 7Z" fill="#7367F0"></path></svg>
            </button>
            <h2 class="analytics-community__title-link">
                Добавления сообщества
            </h2>
        </div>
        <div>
            <h2 class="analytics-community__title" id="addCommunityTitle">Подключение Telegram группы (чат)</h2>
        </div>
    </div>

    <!-- <div class="card-body">
        <div class="col-12 d-flex flex-column flex-md-row"> -->
        <div class="channel-connection">
            <!-- Description -->
            <!-- <div class="col-md-6 col-xl-5">
                <p class="">
                    {{ __('community.one_action') }}
                </p>

                <div class="col-12 p-1 mt-1 border">
                    <h5 class="col-12">
                        {{ __('community.add_telegram_bot_group_admin') }}
                    </h5>

                    <h5 class="mt-1">
                        {{ __('community.our_bot') }} {{ '@' . env('TELEGRAM_BOT_NAME') }} — 
                        <span
                            class="text-primary pointer"
                            onclick="copyText('{{ '@' . env('TELEGRAM_BOT_NAME') }}')"
                        >
                            {{ __('base.copy') }}
                        </span>
                    </h5>
                </div>

                @include('common.community.assets.add_user_bot')

                <div class="col-12 mt-1">
                    <p class="">
                        {{ __('community.our_telegram_bot_instructions') }}
                    </p>
                </div>
                
                <div class="col-8 col-sm-5 col-md-7 mt-1 d-flex align-items-center">
                    <i class="col-4 bot-avatar-icon bot-avatar-icon-50"></i>    
                </div>
            </div> -->
            <div class="channel-connection__instructions-adding-bot">
                <div class="channel-connection__add-bot">
                    <p>{{ __('community.add_telegram_bot_channel_admin') }}</p>
                    <div class="channel-connection__copy">
                        {{ __('community.our_bot') }}
                        <span class="channel-connection__copy--titleBot">{{ '@' . env('TELEGRAM_BOT_NAME') }}</span>  — 
                        <span
                            class="channel-connection__copy--text"
                            onclick="copyText('{{ '@' . env('TELEGRAM_BOT_NAME') }}')"
                        >
                            {{ __('base.copy') }}
                        </span>
                    </div>

                    <div class="channel-connection__userbot">
                        <p>Для отображения детальной статистики по Вашему сообществу необходимо добавить следующего бота и выдать ему права администратора:</p>
                        <div class="channel-connection__copy">
                            Юзербот
                            <span class="channel-connection__copy--titleBot">{{ '@' . env('TELEGRAM_USERBOT_NAME') }}</span>  — 
                            <span
                                class="channel-connection__copy--text"
                                onclick="copyText('{{ '@' . env('TELEGRAM_USERBOT_NAME') }}')"
                            >
                                {{ __('base.copy') }}
                            </span>
                        </div>
                    </div>
                </div>
                <!-- @include('common.community.assets.add_user_bot') -->

                <p class="channel-connection__interpretation">В результатах поиска  могут отображаться и другие боты похожие на наш. Обратите внимание на аватар. </p>

                <div class="channel-connection__img-bot">
                    <p>У нашего бота он такой:</p>
                    <img src="/images/robot.png" alt="Robot-bot">
                </div>
            </div>

            <!-- Load data -->
            <!-- <div
                class="col-md-4 d-flex flex-column justify-content-center ms-md-3 mt-2 mt-md-0"
                data-community-answer-container="Telegram-group"
            >
                <div
                    class="d-flex flex-column align-items-center"
                    data-community-answer-loading="group"
                >
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">
                            Loading...
                        </span>
                    </div>

                    <p class="mt-1">
                        {{ __('community.waiting') }}
                    </p>
                </div>
                
                <div
                    class="col-md-8"
                    data-community-answer-success-message
                ></div>
            </div> -->

            <div
                class="channel-connection__add-channel"
                data-community-answer-container="Telegram-group"
            >
                <div
                    class="d-flex flex-column align-items-center"
                    data-community-answer-loading="group"
                >
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">
                            Loading...
                        </span>
                    </div>

                    <p class="mt-1">
                        {{ __('community.waiting') }}
                    </p>
                </div>

                <div data-community-answer-success-message></div>

                <!-- <div  data-community-answer-success-message>
                    <div class="channel-connection__add-channel-wrap">
                        <div class="channel-connection__connected-community">
                            <div class="channel-connection__image">
                                <img src="/images/avatars/1.png">
                            </div>
                            <div class="channel-connection__description">
                                <p class="channel-connection__channel">Канал Димы Коваля</p>
                                <div class="channel-connection__messenger">
                                    <img src="/images/icons/social/telegram.png">
                                    <p class="profile__text">мессенджер</p>
                                </div>
                            </div>
                        </div>
                        <span class="channel-connection__connected">Подключено</span>
                    </div>
                    <a href="{{route('profile.communities.list')}}" class="button-empty button-empty--primary">Перейти к списку подключённых сообществ</a>
                </div> -->
            </div>
        </div>
    <!-- </div> -->
            

        <!-- <div class="d-flex justify-content-start mt-2">
            <button
                data-tab-btn="main"
                class="btn btn-primary waves-effect waves-float waves-light"
                onclick="CreateCommunityPage.createCommunityBot.onClickTab(this);
                    CreateCommunityPage.createCommunityBot.stopSetInterval()"
            >
                <i data-feather="arrow-left" class="font-medium-1"></i>
                <span class="align-middle d-sm-inline-block d-none">
                    {{ __('base.back') }}
                </span>
            </button>
        </div> -->
    <!-- </div> -->
</div>
