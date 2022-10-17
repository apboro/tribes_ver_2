<div data-tab="main">
    <div class="analytics-community__analytics-wrap projects_creation">
        <div class="analytics-community__title-wrap">
            <a href="{{route('profile.communities.list')}}" class="button-back" id="backButton">
                <svg width="27" height="16" viewBox="0 0 27 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M26 9C26.5523 9 27 8.55228 27 8C27 7.44772 26.5523 7 26 7L26 9ZM0.292893 7.2929C-0.0976311 7.68342 -0.097631 8.31658 0.292893 8.70711L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928934C7.68054 0.538409 7.04738 0.53841 6.65685 0.928934L0.292893 7.2929ZM26 7L1 7L1 9L26 9L26 7Z" fill="#7367F0"></path></svg>
            </a>
            <h2 class="analytics-community__title-link">
                Мои проекты
            </h2>
        </div>
        <div>
            <h2 class="analytics-community__title" id="addCommunityTitle">Добавление сообщества</h2>
        </div>
    </div>

    <!-- <div class="card-body">
        <div class="form form-horizontal">
            <div class="row justify-content-center flex-column flex-md-row">                            



                <div class="col-12 col-md-4 TELEGRAM BLOCK">
                    <div class="card border-secondary custom-1-border-color h-100 mb-1 mb-md-0">
                        <div class="card-body text-center">
                            <i class="telegram-icon telegram-icon-50"></i>
                            <h5 class="card-title">Telegram</h5>
                            
                            <p class="card-text">
                                {{ __('community.what_add') }}
                            </p>

                            <div class="d-flex flex-column align-items-center">
                                


                                <div class="row TELEGRAM-CHANNEL">
                                    <div class="mb-1 col-md-6">
                                        <button
                                            id="telegram_channel_btn" 
                                            data-tab-btn="telegram_channel"
                                            class="btn btn-outline-info waves-effect text-nowrap"
                                            onclick="CreateCommunityPage.createCommunityBot.addCommunity('Telegram', 'channel', this)"
                                        >
                                            {{ __('community.telegram_channel') }}
                                        </button>
                                    </div>
                                </div>


                                <div class="row TELEGRAM-GROUP">
                                    <div class="col-md-6">
                                        <button
                                            id="telegram_group-btn"
                                            data-tab-btn="telegram_group"
                                            class="btn btn-outline-info waves-effect text-nowrap"
                                            onclick="CreateCommunityPage.createCommunityBot.addCommunity('Telegram', 'group', this)"
                                        >
                                            {{ __('community.telegram_group') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{--<div class="col-12 col-md-4 mx-md-1 DISCORD BLOCK">
                    <div class="card border-secondary custom-1-border-color h-100 mb-0">
                        <div class="card-body text-center">
                            <i class="discord-icon discord-icon-50"></i>
                            <h5 class="card-title">Discord</h5>
                            <p class="card-text">
                                {{ __('community.choose_discord_community') }}
                            </p>

                            <div class="d-flex flex-column align-items-center">
                                
                            
                                <div class="row DISCORD-CHAT">
                                    <div class="col-md-6">
                                        <button
                                            id="discord_chat_btn"
                                            data-tab-btn="discord_chat"
                                            class="btn btn-outline-primary waves-effect text-nowrap"
                                        >
                                            {{ __('community.discord_chat') }}
                                        </button>                                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>--}}
            </div>
        </div>
    </div> -->

    <!-- START добавление сообщества -->
    <div class="community-messenger">
        <div class="community-messenger__item-wrap">
            <div class="community-messenger__item">
                <div class="community-messenger__item-image">
                    <img src="/images/icons/social/telegram2.png" alt="Telegram">
                    <h3 class="community-messenger__title">Telegram</h3>
                </div>
                <div class="community-messenger__item-description">
                    <p class="community-messenger__text">Что добавляем?</p>
                    <div class="community-messenger__buttons">
                        <button
                            id="telegram_channel_btn" 
                            data-tab-btn="telegram_channel"
                            class="button-empty button-empty--telegram"
                            onclick="CreateCommunityPage.createCommunityBot.addCommunity('Telegram', 'channel', this)"
                        >{{ __('community.telegram_channel') }}</button>
                        <!-- <a href="#" class="button-empty button-empty--telegram" href="">Канал</a>
                        <a href="#" class="button-empty button-empty--telegram" href="">Группа (чат)</a> -->
                        <button
                            id="telegram_group-btn"
                            data-tab-btn="telegram_group"
                            class="button-empty button-empty--telegram"
                            onclick="CreateCommunityPage.createCommunityBot.addCommunity('Telegram', 'group', this)"
                        >
                            <!-- {{ __('community.telegram_group') }} -->
                            Группа (чат)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END добавление сообщества -->

</div>


