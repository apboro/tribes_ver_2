@extends('common.community.profile')

@section('tab')
    @include('common.template.alert.form_info', ['message' => \Session::get('message'), 'errors' => $errors])

    <section class="community-tab" data-tab="tariffPagePublications">
        <div class="community-tab__header">
            <a
                href="{{ route('community.tariff.list', $community) }}"
                class="community-tab__prev-page-btn"
            >
                <i data-feather="arrow-left" class="font-medium-1"></i>
            </a>

            <p class="community-tab__prev-page-title">Тарифы</p>
            <h2 class="community-tab__title">Публикация тарифов</h2>
        </div>

        <nav class="tab-nav community-tab__nav">
            <ul class="tab-nav__list">               
                <li class="tab-nav__item @if( !request('tab') || request('tab') == 'common') active @endif">
                    <a
                        class="tab-nav__link"
                        href="{{ route('community.tariff.publication', ['community' => $community]) }}"
                    >
                        Сообщение с тарифами в сообщество
                    </a>
                </li>
    
                <li  class="tab-nav__item @if( request('tab') == 'pay') active @endif">
                    <a
                        class="tab-nav__link"
                        href="{{ route('community.tariff.publication', ['community' => $community, 'tab' => 'pay']) }}"
                    >
                        Посадочная веб страница
                    </a>
                </li>
            </ul>
        </nav>

        

        <!-- TABS -->
        @yield('subtab')
    </section>

    
@endsection
