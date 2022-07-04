<div data-tab="main">
    <div class="card-header justify-content-center">
        <h4 class="card-title">
        {{ __('base.add_community') }}
        </h4>
    </div>

    <div class="card-body">
        <div class="form form-horizontal">
            <div class="row justify-content-center flex-column flex-md-row">                            
                <!-- Telegram block -->
                <div class="col-12 col-md-4">
                    <div class="card border-secondary custom-1-border-color h-100 mb-1 mb-md-0">
                        <div class="card-body text-center">
                            <i class="telegram-icon telegram-icon-50"></i>
                            <h5 class="card-title">Telegram</h5>
                            
                            <p class="card-text">
                                {{ __('community.what_add') }}
                            </p>

                            <div class="d-flex flex-column align-items-center">
                                <!-- Telegram-канал -->
                                <div class="row">
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

                                <!-- Telegram-группа -->
                                <div class="row">
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

                <!-- Discord block -->
                {{--<div class="col-12 col-md-4 mx-md-1">
                    <div class="card border-secondary custom-1-border-color h-100 mb-0">
                        <div class="card-body text-center">
                            <i class="discord-icon discord-icon-50"></i>
                            <h5 class="card-title">Discord</h5>
                            <p class="card-text">
                                {{ __('community.choose_discord_community') }}
                            </p>

                            <div class="d-flex flex-column align-items-center">
                                <!-- Discord-чат -->
                                <div class="row">
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
    </div>
</div>
