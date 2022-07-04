@extends('common.cash.list')
@section('tab')
    <div class="" data-tab="PaymentsCardListPage">
        <!-- Карты -->
        <div id="cards_container" class="row align-items-center">
            <!-- Карт нет -->
            <div id="empty_label" class="alert alert-info hidden" role="alert">
                <div class="alert-body d-flex align-items-center">
                    <i data-feather='info'></i>
                    <span class="ms-1">
                        {{ __('payment.no_connected_cards') }}
                    </span>
                </div>
            </div>

            <!-- Спиннер карт -->
            <div id="cards_spinner" class="col-1 mb-2">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        
            <!-- Добавить карту -->
            <div class="col-12 col-md-4 mb-2"> 
                <button
                    id="add_card_btn"
                    class="btn btn-outline-success waves-effect w-100 d-flex align-items-center justify-content-center"
                    onclick="PaymentsPage.paymentsCardListPage.addCard()"
                >
                    <i data-feather='plus'></i>
                    <span class="ms-1 d-none d-sm-inline-block">
                        {{ __('payment.add_payout_card') }}
                    </span>
                </button>
            </div>
        </div>

        <!-- Спиннер банка -->
        <div id="bank_spinner" class="col-1 mt-2 text-center w-100 hidden">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <!-- Добавление карты -->
        <div id="add_card_container" class="col-12 hidden">
            <div class="card">
                <div class="card-body">
                    <iframe id="card_iframe" src=""></iframe>
                </div>

                <div class="card-footer">
                    <button
                        class="btn btn-success d-flex align-items-center"
                        onclick="PaymentsPage.paymentsCardListPage.finishAddingCard()"
                    >
                        <i data-feather='check'></i>
                        <span class="ms-1 d-none d-sm-inline-block">
                            {{ __('payment.finish_adding') }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
