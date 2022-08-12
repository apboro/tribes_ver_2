@extends('common.community.profile')

@section('tab')
    @include('common.template.alert.form_info', ['message' => \Session::get('message'), 'errors' => $errors])

    <section class="form-control-repeater" data-tab="tariffPageSettings">
        <div class="row">
            <!-- Invoice repeater -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-8 col-xl-10">
                            <h4 class="card-title">
                                Публикация тарифов в сообществе
                            </h4>
                        </div>
                        
                        <a
                            href="{{ route('community.tariff.list', $community) }}"
                            class="btn btn-outline-primary custom waves-effect"
                        >
                            <i data-feather="arrow-left" class="font-medium-1"></i>
                            
                            <span class="align-middle d-sm-inline-block d-none">
                                {{ __('base.back') }}

                            </span>
                        </a>
                    </div>
                </div>
            
                <!-- Nav -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a
                            class="nav-link @if( !request('tab') || request('tab') == 'common') active @endif"
                            href="{{ route('community.tariff.publication', ['community' => $community]) }}"
                        >
                            Сообщение с тарифами в сообщество
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link @if( request('tab') == 'pay') active @endif"
                            href="{{ route('community.tariff.publication', ['community' => $community, 'tab' => 'pay']) }}"
                        >
                            Посадочная веб-страница с тарифами
                        </a>
                    </li>
                </ul>
                    
                <!-- TABS -->
                @yield('subtab')    
            </div>
        </div>
    </section>
@endsection
