@extends('layouts.auth')

@section('og')
    <meta property="og:title" content="{{$community->tariff->title}}"/>
    <meta property="og:description" content="{{$community->tariff->main_description}}"/>
    <meta property="og:image" content="@if ($community->tariff->getMainImage()){{ $community->tariff->getMainImage()->url }}@endif"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content= "{{ route('community.tariff.payment', ['hash' => App\Helper\PseudoCrypt::hash($community->id, 8)]) }}" />
@endsection

@section('content')
    <div class="auth-inner my-2" data-plugin="TariffSelectionPage">
        <div class="card mb-0">
            <div class="col-12">
                <img src="@if ($community->tariff->getMainImage()){{ $community->tariff->getMainImage()->url }}@endif" alt="" class="w-100">
            </div>  
            
            <div class="card-body d-flex flex-column align-items-center">
                <div class="col-12">
                    <h2 class="card-title text-center">
                        {{ $community->tariff->title }}
                    </h2>
                </div>

                <div class="col-12">
                    {!! $community->tariff->main_description !!}
                </div>

                <div class="col-12 row">
                    @if ($community->tariff->test_period !== 0 && env('USE_TRIAL_PERIOD', true))
                        <a
                            href="https://t.me/{{ env('TELEGRAM_BOT_NAME') }}?start={{ $community->id }}trial"
                            class="btn btn-outline-success waves-effect mb-1"
                        >
                        Пробный период — {{ $community->tariff->test_period }} {{App\Traits\Declination::defineDeclination($community->tariff->test_period)}}</a>
                    @endif
                    @foreach ($community->tariff->variants as $tariff)
                        @if ($tariff->isActive == true && $tariff->price !== 0)
                            <button
                                class="btn btn-outline-success waves-effect mb-1"
                                onclick="TariffSelectionPage.openRightsModal({ 
                                    communityName: '{{ $community->title }}',
                                    communityTariff: '{{ $tariff->title }}',
                                    communityTariffID: '{{ $tariff->id }}',
                                    communityAmount: '{{ $tariff->price }}',
                                    url: `{{ $community->getTariffPayLink(['amount' => $tariff->price,'currency' => 0,'type' => 'tariff'], $community) }}`
                                })"
                            >
                                {{ $tariff->title }} {{ $tariff->price }}₽ — {{ $tariff->period }} {{App\Traits\Declination::defineDeclination($tariff->period)}}
                            </button>
                        @endif
                    @endforeach

                </div>

                <div class="col-12 mt-1">
                    <span>
                        Выбирая тарифный план вы соглашаетесь с 
                        <a href="{{ route('terms.index') }}" target="_blank" class="btn-link">правилами пользования</a>
                         и 
                        <a href="{{ route('privacy.index') }}" target="_blank" class="btn-link">политикой конфиденциальности.</a>
                    </span>
                </div>

                <p class="col-12 mt-1">
                    Платежи за продление доступа списываются автоматически. Вы можете отказаться от продления доступа в любой момент просто покинув сообщество.
                </p>
            </div>
        </div>
    </div>

    @include('common.template.service_container')
@endsection
