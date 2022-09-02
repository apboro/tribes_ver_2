<!-- <div class="right col-12 col-xl-6">
    <label class="form-label">
            {{ __('base.preview') }}
    </label>
    <div id="pay_block_preview_container" class="d-flex flex-column align-items-center">
        <p class="height: 10px; width: 100%; background-color: lightblue; border-top-right-radius: 6px; border-top-left-radius: 6px;"></p>
        <div class="d-flex flex-row">
            <div id="preview_img" class="col-12 mb-2" style="margin-top:10px; height: 260px; background-color: #ebe9f1;">
                <img
                    src="@if($community->tariff && $community->tariff->getMainImage()){{$community->tariff->getMainImage()->url }}@else/images/no-image.svg @endif"
                    alt=""  
                    class="active-image__img rounded w-100 h-100"
                    style="object-fit: contain;"
                    data-default-image
                >

                <canvas class="hide w-100 h-100" style="object-fit: contain;"></canvas>
            </div>

            <div class="">
                <div class="">
                    <h2 id="pay_block_preview_title" class="card-title">{{$community->tariff->title ?? ''}}</h2>
                </div>
                
                <div class="align-self-start">
                    <div id="pay_block_preview_editor_container" class=""></div>
                </div>
            </div>
        </div>
    
        @if($community->tariff)
        <div class="col-12 row">
            @if ($community->tariff->test_period !== 0 && env('USE_TRIAL_PERIOD', true))
            <span class="btn btn-outline-success waves-effect mb-1">
                Пробный период — {{$community->tariff->test_period}} {{App\Traits\Declination::defineDeclination($community->tariff->test_period)}}
            </span>
            @endif
            
            @foreach ($community->tariff->getTariffVariants() as $tariff)
                @if ($tariff->isActive == true && $tariff->price !== 0)
                    <span class="btn btn-outline-success waves-effect mb-1">
                        {{$tariff->title}} {{$tariff->price}}₽ — {{$tariff->period}} {{App\Traits\Declination::defineDeclination($tariff->period)}}
                    </span>
                @endif
            @endforeach
            
        </div>
        @endif
    </div>
</div> -->

<div class="">
    <label class="form-label-red">
            {{ __('base.preview') }}
    </label>
    <div 
        id="pay_block_preview_container" 
        class="tariff-public--preview"
        data-plugin="TariffSelectionPage"
    >
        <div class="tariff-public__content">
            <div id="preview_img" class="tariff-public__image">
                <img
                    src="@if($community->tariff && $community->tariff->getMainImage()){{$community->tariff->getMainImage()->url }}@else/images/no-image.svg @endif"
                    alt=""  
                    data-default-image
                >

                <canvas class="hide" style="object-fit: contain; width: 100%;"></canvas>
            </div>

            <div class="tariff-public__description">
                <h2 id="pay_block_preview_title" class="tariff-public__title">
                    {{$community->tariff->title ?? ''}}
                </h2>
                
                <div class="tariff-public__editor-content">
                    <div id="pay_block_preview_editor_container" class=""></div>
                </div>
            </div>
        </div>
    
        @if($community->tariff)
        <div class="tariff-public__variants">
            <h3 class="tariff-public__variants-title">
                Приобрести доступ
            </h3>

            <ul class="tariff-public__list tariff-public__list--preview">
                @foreach ($community->tariff->getTariffVariants() as $tariff)
                    @if ($tariff->isActive == true && $tariff->price !== 0)
                    <li class="tariff-public__item">
                        <span
                            class="tariff-public__variant"
                        >
                            <div class="tariff-public__variant-header">
                                <h4 class="tariff-public__variant-title" title="{{ $tariff->title }}">
                                    {{ $tariff->title }}
                                </h4>
                            </div>
                            
                            <div class="tariff-public__variant-wrapper">
                                <span class="tariff-public__time">
                                    {{ $tariff->period }} {{App\Traits\Declination::defineDeclination($tariff->period)}}
                                </span>
    
                                <div class="tariff-public__price-wrapper">
                                    <span class="tariff-public__price-discount"></span>
    
                                    <span class="tariff-public__price">
                                        {{ $tariff->price }}₽
                                    </span>
    
                                    <span class="tariff-public__old-price"></span>
                                </div>
                            </div>
                        </span>
                    </li>
                    @endif
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
