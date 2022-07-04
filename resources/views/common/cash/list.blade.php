@extends('layouts.app')

@section('content')
    <div class="content-wrapper container-xxl p-0" data-plugin="PaymentsPage">
        <!-- Breadcrumbs block -->
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0 border-0">
                            {{ __('base.finance') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between">
            <!-- Tabs -->
            @include('common.cash.assets.nav')

            <!-- Balance -->
            <div class="d-flex">
                <div class="card">
                    <div class="btn cursor-default">
                        <span class="d-none d-md-inline-block">{{ __('payment.your_balance') }}: </span>
                        <span>{{ Auth::user()->getBalance() }} ₽</span>
                    </div>                        
                </div>  
            </div>
        </div>
        
        <!-- Контент -->
        <div class="tab-content">            
            @yield('tab')
        </div>
    </div>
@endsection
