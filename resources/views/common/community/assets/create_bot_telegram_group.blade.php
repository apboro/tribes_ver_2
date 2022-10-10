<div class="hidden" data-tab="telegram_group" data-pagename='Подключение Telegram группы (чат)'>
    <!-- <div class="card-header">
        <h4 class="card-title">
            {{ __('community.telegram_group_connection') }}
        </h4>
    </div> -->

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
                    <p>{{ __('community.add_telegram_bot_group_admin') }}</p>
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
                data-community-answer-container="Telegram-channel"
            >
                <!-- <div
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
                </div> -->
                <div  data-community-answer-success-message>
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
                </div>
            </div>
        </div>
    </div>
            

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
    </div>
</div>
