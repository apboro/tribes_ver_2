@extends('common.community.profile')

@section('tab')
    @include('common.template.alert.form_info', ['message' => \Session::get('message'), 'errors' => $errors])

    <section
        class="community-tab"
        data-tab="tariffPagePublications"
    >
        <div class="community-tab__header">
            <a
                href="{{ route('project.tariffs', ['project' => $community->project_id ?? 'c', 'community' => $community->id]) }}"
                class="button-back community-tab__prev-page-btn"
            >
                <svg width="27" height="16" viewBox="0 0 27 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M26 9C26.5523 9 27 8.55228 27 8C27 7.44772 26.5523 7 26 7L26 9ZM0.292893 7.2929C-0.0976311 7.68342 -0.097631 8.31658 0.292893 8.70711L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928934C7.68054 0.538409 7.04738 0.53841 6.65685 0.928934L0.292893 7.2929ZM26 7L1 7L1 9L26 9L26 7Z" fill="#7367F0"></path></svg>
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
