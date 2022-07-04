<div class="col-sm-4 col-md-3 col-lg-2">
    <div class="card community-item">
        <a href="{{ route('community.statistic', ['community' => $community->id]) }}">
            <img
                class="card-img-top"
                src="@if($community->image !== null) {{$community->image}} @else /images/no-image.svg @endif"
                alt="Card image cap"
            />

            <div class="card-body">
                <h4 class="card-title community-item__title" title="{{ $community->title }}">
                    {{ $community->title }}
                </h4>

                <span class="card-text text-muted d-flex align-items-center justify-content-center">
                    <span>{{ __('base.follow') }}</span>
                    <i data-feather="chevrons-right" class="font-medium-3 ms-1"></i>
                </span>

                <div class="badge-list badge badge-up community-badge">
                @if($community->isTelegram())
                    <span class="badge-glow bg-info">
                        Telegram
                    </span>    
                @else
                    <span class="badge-glow bg-primary">
                        Discord
                    </span>    
                @endif
                
                @if($community->isTelegramGroup())
                    <span class="badge-glow bg-success">
                        Группа
                    </span>
                @elseif($community->isTelegramChannel())
                    <span class="badge-glow bg-warning">
                        Канал
                    </span>
                @endif
                </div>
            </div>
        </a>
    </div>
</div>
