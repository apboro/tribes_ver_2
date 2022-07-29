<div class="right col-12 col-xl-6">
    <div id="pay_block_preview_container" class="d-flex flex-column align-items-center">
        <div id="preview_img" class="col-12 mb-2" style="margin-top:10px; height: 260px; background-color: #ebe9f1;">
            <img
                src="@if($community->tariff && $community->tariff->getMainImage()){{ $community->tariff->getMainImage()->url }}@else/images/no-image.svg @endif"
                alt=""  
                class="active-image__img rounded w-100 h-100"
                style="object-fit: contain;"
                data-default-image
            >

            <canvas class="hide w-100 h-100" style="object-fit: contain;"></canvas>
        </div>
        
        <div class="">
            <h2 id="pay_block_preview_title" class="card-title">{{$community->tariff->title ?? ''}}</h2>
        </div>
        
        <div class="align-self-start">
            <div id="pay_block_preview_editor_container" class=""></div>
        </div>
        @if($community->tariff)
        <div class="col-12 row">
            @if ($community->tariff->test_period !== 0 && env('USE_TRIAL_PERIOD'))
            <button class="btn btn-outline-success waves-effect mb-1">
                Пробный период — {{$community->tariff->test_period}} {{App\Traits\Declination::defineDeclination($community->tariff->test_period)}}
            </button>
            @endif
            @foreach ($community->tariff->variants as $tariff)
                @if ($tariff->isActive == true && $tariff->price !== 0)
                    <button class="btn btn-outline-success waves-effect mb-1">
                        {{$tariff->title}} {{$tariff->price}}₽ — {{$tariff->period}} {{App\Traits\Declination::defineDeclination($tariff->period)}}
                    </button>
                @endif
            @endforeach
            
        </div>
        @endif
    </div>
</div>
