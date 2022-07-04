<div class="col-md-6 col-lg-4 col-xl-3 mb-2">
    <div class="card card-profile h-100">
        <div class="img-fluid card-img-top author-profile-messenger__head author-profile-messenger__head--telegram">
            <h2 class="pt-2 pb-4 author-profile-messenger__title">Telegram</h2>
        </div>

        <div class="card-body">
            <div class="profile-image-wrapper">
                <div class="profile-image">
                    <div class="avatar cursor-default">
                        @if(Auth::user()->hasTelegramAccount())
                        <img src="{{ ( $ty && $ty->photo_url ) ? $ty->photo_url : '/images/no-user-avatar.svg' }}" alt="" class="avatar-content size-50">
                        @else
                        <img src="/ico/telegram.svg" alt="" class="avatar-content size-50">
                        @endif
                    </div>
                </div>
            </div>

            <h3
                class="community-item__title"
                title="{{ $ty ? $ty->publicName() : null }}"    
            >
                {{ $ty ? $ty->publicName() : null }}
            </h3>   
        </div>

        <div class="card-footer text-center">
            @if(Auth::user()->hasTelegramAccount())
                <form method="post" action="{{ route('author.profile.detach.telegram') }}">
                    @csrf
                    <button type="submit" class="btn btn-flat-danger waves-effect">
                        {{ __('base.disable') }}
                    </button>
                </form>
            @else
                <script
                    async
                    src="https://telegram.org/js/telegram-widget.js?15"
                    data-telegram-login="{{ env('TELEGRAM_BOT_NAME') }}"
                    data-size="large"
                    data-onauth="onTelegramAuth(user)"
                ></script>
            @endif
        </div>
    </div>

</div>

