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
                    
        <!-- TABS -->
        @yield('subtab')    
    </section>
@endsection
