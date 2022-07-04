@extends('layouts.app')

@section('content')
    <div class="content-wrapper container-xxl p-0" data-plugin="PaymentsCardPage">
        <!-- Breadcrumbs block -->
        <div class="content-header row align-items-center">
            <div class="content-header-left col-9 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">
                            {{ __('payment.add_card') }}
                        </h2>

                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('payment.list') }}">
                                        {{ __('base.finance') }}
                                    </a>
                                </li>

                                <li class="breadcrumb-item active">
                                    {{ __('payment.add_card') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-header-right col-3">
                <div class="text-sm-end text-md-end text-end">
                    <div class="mb-1 breadcrumb-right">
                        <a
                            href="{{ route('payment.list') }}"
                            class="btn btn-outline-primary custom waves-effect"
                        >
                            <i data-feather="arrow-left" class="font-medium-1"></i>
                            
                            <span class="align-middle d-sm-inline-block d-none">
                                {{ __('base.back') }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Horizontal Wizard -->
        <section class="horizontal-wizard">
            <div class="bs-stepper horizontal-wizard-example">
                <!-- Tab panel -->
                @include('common.cash.assets.form_steps')

                <div class="bs-stepper-content">
                    <!-- CURRENCY -->
                    <div
                        id="add_card"
                        class="content"
                        role="tabpanel"
                        aria-labelledby="add_card_trigger"
                    >
                        @include('common.cash.assets.form_currency')
                    </div>

                    <!-- EMAIL -->
                    <div
                        id="email_block"
                        class="content"
                        role="tabpanel"
                        aria-labelledby="email_block_trigger"
                    >
                        @include('common.cash.assets.form_email')
                    </div>

                    <!-- CARD NUMBER -->
                    <div
                        id="card_number_block"
                        class="content"
                        role="tabpanel"
                        aria-labelledby="card_number_block_trigger"
                    >
                        @include('common.cash.assets.form_card_number')
                    </div>
                </div>
            </div>
        </section>
        <!-- /Horizontal Wizard -->
    </div>
@endsection
