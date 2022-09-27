<div class="col-12 p-1 mt-1 border">
    <h5 class="col-12">
        {{ __('community.add_telegram_userbot_admin') }}
    </h5>

    <h5 class="mt-1">
        {{ __('community.user_bot') }} {{ '@' . env('TELEGRAM_USERBOT_NAME', 'не сконфигурирован') }} —
        <span
                class="text-primary pointer"
                onclick="copyText('{{ '@' . env('TELEGRAM_USERBOT_NAME', 'не сконфигурирован') }}')"
        >
                            {{ __('base.copy') }}
                        </span>
    </h5>
</div>