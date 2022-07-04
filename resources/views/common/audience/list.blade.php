@extends('layouts.app')
@section('content')
    <div class="content-wrapper container-xxl p-0" data-plugin="PaymentsPage">
        <!-- Breadcrumbs block -->
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0 border-0">
                            {{ __('base.audience') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <form action="{{ route('audience.list') }}" method="get">
                <div class="card-body">
                    @include('common.audience.assets.filters')

                    <!-- Table -->
                    <div class="row align-items-center mt-1">
                        <div class="card-datatable table-responsive">
                            @include('common.audience.assets.table')
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                @if ($followers->total() > 0)
                    <div class="card-footer">
                        <!-- Pagination -->
                        <div class="d-flex align-items-center justify-content-between flex-wrap mx-0">

                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_2_paginate">
                                {{ $followers->onEachSide(1)->appends(request()->input())->links(true ? 'vendor.pagination.bootstrap-4' : 'vendor.pagination.table-links') }}
                            </div>
                        </div>
                    </div>
                @endif

            </form>
        </div>
    </div>
@endsection
