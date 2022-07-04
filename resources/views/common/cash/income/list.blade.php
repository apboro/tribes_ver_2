@extends('common.cash.list')
@section('tab')

    <section id="column-search-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form action="" method="get">
                        <div class="card-body">
                            @include('common.cash.assets.income_filter')                             
                            
                            <!-- TABLE -->
                            <div class="row align-items-center mt-1">
                                <div class="card-datatable table-responsive">
                                    @include('common.cash.assets.income_table')
                                </div>
                            </div>

                            
                        </div>
    
                        @if ($payments->total() > 0)
                            <div class="card-footer">
                                <!-- Pagination -->
                                <div class="d-flex align-items-center justify-content-between flex-wrap mx-0">
                                    <div class="dataTables_info" id="DataTables_Table_2_info" role="status" aria-live="polite">
                                        {{ __('base.shown_from') }} {{ $payments->perPage() * $payments->currentPage() - $payments->perPage() + 1 }} {{ __('base.to') }} {{ $payments->lastItem() }} {{ __('base.from') }} {{ $payments->total() }} {{ __('base.entries_rus_low') }}
                                    </div>
                                
                                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_2_paginate">
                                        {{ $payments->onEachSide(1)->appends(request()->input())->links(!Agent::isMobile() ? 'vendor.pagination.bootstrap-4' : 'vendor.pagination.table-links') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection