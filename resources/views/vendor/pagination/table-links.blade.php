@if ($paginator->hasPages())

    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_2_paginate">
        <ul class="pagination justify-content-end">

            @if ($paginator->onFirstPage())
                <li class="paginate_button page-item previous disabled" id="DataTables_Table_2_previous">
                    <a href="#" aria-controls="DataTables_Table_2" data-dt-idx="0" tabindex="0" class="page-link">{!! __('pagination.previous') !!}</a>
                </li>
            </span>
            @else
                <li class="paginate_button page-item previous" id="DataTables_Table_2_previous">
                    <a href="{{ $paginator->previousPageUrl() }}" aria-controls="DataTables_Table_2" data-dt-idx="0" tabindex="0" class="page-link">{!! __('pagination.previous') !!}</a>
                </li>
            @endif

            @if ($paginator->hasMorePages())
                    <li class="paginate_button page-item next" id="DataTables_Table_2_next">
                        <a href="{{ $paginator->nextPageUrl() }}" aria-controls="DataTables_Table_2" data-dt-idx="1" tabindex="0" class="page-link">{!! __('pagination.next') !!}</a>
                    </li>
            @else
                    <li class="paginate_button page-item next disabled" id="DataTables_Table_2_next">
                        <a href="#" aria-controls="DataTables_Table_2" data-dt-idx="1" tabindex="0" class="page-link">{!! __('pagination.next') !!}</a>
                    </li>
            @endif
        </ul>
    </div>
@endif
