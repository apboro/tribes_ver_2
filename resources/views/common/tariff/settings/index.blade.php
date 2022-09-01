@extends('common.community.profile')

@section('tab')
    @include('common.template.alert.form_info', ['message' => \Session::get('message'), 'errors' => $errors])

    <section class="community-tab" data-tab="tariffPageSettings">
        <div class="community-tab__header">
            <a
                href="{{ route('community.tariff.list', $community) }}"
                class="community-tab__prev-page-btn"
            >
                <i data-feather="arrow-left" class="font-medium-1"></i>
            </a>

            <p class="community-tab__prev-page-title">Тарифы</p>
            <h2 class="community-tab__title">
                {{ __('tariff.settings_title') }}
            </h2>
        </div>


        <div class="row">
            <!-- Invoice repeater -->
            <div class="col-12">
                <!-- Nav -->
                <!-- <a
                    class="nav-link @if( !request('tab') || request('tab') == 'common') active @endif"
                    href="{{ route('community.tariff.settings', ['community' => $community]) }}"
                >
                    {{ __('base.common') }}
                </a> -->
                    
                <!-- TABS -->
                @yield('subtab')    
            </div>
        </div>
    </section>
@endsection
