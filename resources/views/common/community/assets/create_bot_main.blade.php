<div data-tab="main" data-pagename='Добавление сообщества'>
    <!-- <div class="card-header justify-content-center">
        <h4 class="card-title">
        {{ __('base.add_community') }}
        </h4>
    </div> -->

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


