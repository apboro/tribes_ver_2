<li>
    <div class="community-tariff-card community-tariff__item">
        <div class="community-tariff-card__header">
            <h4
                class="community-tariff-card__title"
                t title="{{ $donate->title }}"
            >
                {{ $donate->title }}
            </h4>
            
            <a
                href="{{ route('community.donate.add', ['community' => $community, 'id' => $donate->id]) }}"
                class="button-text button-text--primary button-text--only-icon community-tariff-card__edit"
            >
                <!-- <i data-feather='edit' class="font-medium-1"></i> -->
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="icon__stroke" fill-rule="evenodd" clip-rule="evenodd" d="M15.5858 4.41421C16.3668 3.63317 17.6332 3.63317 18.4142 4.41421L19.5858 5.58579C20.3668 6.36684 20.3668 7.63316 19.5858 8.41421L8.58579 19.4142C8.21071 19.7893 7.70201 20 7.17157 20L4 20L4 16.8284C4 16.298 4.21071 15.7893 4.58579 15.4142L15.5858 4.41421Z" stroke="#B5B4B8"/>
                    <path class="icon__stroke" d="M14 6L18 10" stroke="#B5B4B8"/>
                </svg>
            </a>
        </div>

        <div class="community-tariff-card__donate-text">
            <span>Сумма платежей:</span>
            <span>{{ array_sum($donate->getSumDonateByIndex()) }}₽</span>
        </div>

        <div class="community-tariff-card__donate-text">
            <span>Кол-во платежей:</span>
            <span>{{ count($donate->getSumDonateByIndex()) }}</span>
        </div>
        
        <div class="community-tariff-card__donate-text">
            <span>Индекс:</span>
            <span>{{ $donate->index }}</span>
        </div>

        <div class="community-tariff-card__donate-text">
            <span>Сообщество:</span>
            <span>{{ $community->title }}</span>
        </div>
    </div>
    <div class="community-settings__inline-command list">
        <span class="form-label-red">
            Инлайн команда одного доната
        </span>
        <a 
            class="community-settings__inline-link" 
            onclick="copyText('{{ '@' . env('TELEGRAM_BOT_NAME') }} {{ $donate ? $donate->inline_link : 'Создастся при сохранении' }}')"
        >
            {{ '@' . env('TELEGRAM_BOT_NAME') }} {{ $donate->inline_link }}
        </a>
    </div>
</li>
