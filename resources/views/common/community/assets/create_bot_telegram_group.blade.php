<div class="hidden" data-tab="telegram_group">
    <div class="card-header">
        <h4 class="card-title">
            {{ __('community.telegram_group_connection') }}
        </h4>
    </div>

    <div class="card-body">
        <div class="col-12 d-flex flex-column flex-md-row">
            <!-- Description -->
            <div class="col-md-6 col-xl-5">
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

                <div class="col-12 mt-1">
                    <p class="">
                        {{ __('community.our_telegram_bot_instructions') }}
                    </p>
                </div>
                
                <div class="col-8 col-sm-5 col-md-7 mt-1 d-flex align-items-center">
                    <i class="col-4 bot-avatar-icon bot-avatar-icon-50"></i>    
                </div>
            </div>

            <!-- Load data -->
            <div
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
            </div>
        </div>
            

        <div class="d-flex justify-content-start mt-2">
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
        </div>
    </div>
</div>
