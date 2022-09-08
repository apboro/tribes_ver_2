@extends('layouts.auth')

@section('og')
    <meta property="og:locale" content="ru_RU" />
    <meta property="og:title" content="{{$community->tariff->title}}"/>
    <meta property="og:description" content="{{mb_strimwidth(strip_tags($community->tariff->main_description), 0, 250, "...") }}"/>
    <meta property="og:image" content="@if ($community->tariff->getMainImage()){{ asset($community->tariff->getMainImage()->url) }}@endif"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content= "{{ route('community.tariff.payment', ['hash' => App\Helper\PseudoCrypt::hash($community->id, 8)]) }}" />
@endsection

@section('content')
    <div
        class="tariff-public"
        data-plugin="TariffSelectionPage"
    >
        <div class="tariff-public__content">
            <div class="tariff-public__image">
                <img
                    src="@if ($community->tariff->getMainImage()){{ $community->tariff->getMainImage()->url }} @else /images/no-tariff-img-telegram.jpg @endif"
                    alt=""
                    @if ($community->tariff->getMainImage()) data-no-image="true" @endif
                >

                <!-- <span class="decor-1"></span>
                <span class="decor-2"></span> -->
            </div>

            <div class="tariff-public__description">
                <h2 class="tariff-public__title">
                    {{ $community->tariff->title }}
                </h2>

                <div class="tariff-public__editor-content">
                    {!! $community->tariff->main_description !!}
                </div>
            </div>
        </div>

        <div class="tariff-public__variants">
            <h3 class="tariff-public__variants-title">
                Приобрести доступ
            </h3>

            <ul class="tariff-public__list">
                @foreach ($community->tariff->getTariffVariants() as $tariff)
                    @if ($tariff->isActive == true && $tariff->price !== 0)
                    <li class="tariff-public__item">
                        <button
                            class="tariff-public__variant"
                            onclick="TariffSelectionPage.openRightsModal({ 
                                communityName: '{{ $community->title }}',
                                communityTariff: '{{ $tariff->title }}',
                                communityTariffID: '{{ $tariff->id }}',
                                communityAmount: '{{ $tariff->price }}',
                                url: `{{ $community->getTariffPayLink(['amount' => $tariff->price,'currency' => 0,'type' => 'tariff'], $community) }}`
                            })"
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
                        </button>
                    </li>
                    @endif
                @endforeach
            </ul>
        </div>

        <div class="tariff-public__rights">
            <p class="tariff-public__right">
                Выбирая тарифный план вы соглашаетесь с 
                <a href="{{ route('terms.index') }}" target="_blank" class="link">правилами пользования</a>
                    и 
                <a href="{{ route('privacy.index') }}" target="_blank" class="link">политикой конфиденциальности.</a>
            </p>

            <p class="tariff-public__right">
                Платежи за продление доступа списываются автоматически. Вы можете отказаться от продления доступа в любой момент просто покинув сообщество.
            </p>
        </div>
    </div>


    

    @include('common.template.service_container')
@endsection
