<table class="dt-complex-header table table-bordered table-responsive">
    <thead>
        <tr>
            <th colspan="2">
                {{ __('base.date') }}
            </th>
            
            <th rowspan="2"  class="align-middle">
                {{ __('payment.total_amount') }}
            </th>

            <th rowspan="2" width="200" class="align-middle">
                <code
                    class="d-inline-flex"
                    data-bs-toggle="tooltip"
                    data-bs-placement="bottom"
                    data-bs-original-title="{{ __('payment.status_instructions') }}"
                >
                    {{ __('base.status') }}
                    <i data-feather='info' class="font-medium-1 ms-1"></i>
                </code>
            </th>
        </tr>

        <tr>
            <th rowspan="1" width="200">
                <code
                    class="d-inline-flex"
                    data-bs-toggle="tooltip"
                    data-bs-placement="bottom"
                    data-bs-original-title="{{ __('payment.start_instructions') }}"
                >
                    {{ __('payment.start') }}
                    <i data-feather='info' class="font-medium-1 ms-1"></i>
                </code>
            </th>

            <th rowspan="1" width="200">
                <code
                    class="d-inline-flex"
                    data-bs-toggle="tooltip"
                    data-bs-placement="bottom"
                    data-bs-original-title="{{ __('payment.withdrawal_instructions') }}"
                >
                    {{ __('base.withdrawal') }}
                    <i data-feather='info' class="font-medium-1 ms-1"></i>
                </code>    
            </th>
        </tr>
    </thead>

    <tbody>
            {{--@foreach()--}}
            @forelse($accumulations as $accumulation)

                <tr class="odd">
                    <td valign="top p-0" colspan="1" class="dataTables_empty">
                        {{ $accumulation->started_at->format('d.m.Y H:i:s') }}
                    </td>

                    <td valign="top p-0" colspan="1" class="dataTables_empty">
                        {{ $accumulation->ended_at->format('d.m.Y H:i:s') }}
                    </td>

                    <td valign="top p-0" colspan="1" class="dataTables_empty">
                        {{ $accumulation->amount / 100 }}
                    </td>

                    <td valign="top p-0" colspan="1" class="dataTables_empty">
                        @switch($accumulation->status)
                            @case('active')
                                <span
                                    class="btn btn-success"
                                    onclick="PaymentsPage.outcomePage.showCards(this)"
                                    data-sp-accumulation-id="{{ $accumulation->SpAccumulationId }}"
                                >
                                {{ __('base.withdraw') }}
                                </span>
                            @break

                            @case('closed')
                                <span class="btn btn-secondary disabled">
                                    {{ __('base.withdrawn') }}
                                </span>
                            @break
                        @endswitch
                    </td>
                </tr>
                @empty
                <tr class="odd">
                    <td valign="top" colspan="3" class="dataTables_empty">
                        {{ __('base.no_entries') }}
                    </td>
                </tr>
                @endforelse
{{--                <tr class="odd">--}}
{{--                    <td valign="top p-0" colspan="1" class="dataTables_empty">--}}
{{--                        18.03.2022--}}
{{--                    </td>--}}

{{--                    <td valign="top p-0" colspan="1" class="dataTables_empty">--}}
{{--                        18.03.2022--}}
{{--                    </td>--}}

{{--                    <td valign="top p-0" colspan="1" class="dataTables_empty">--}}
{{--                        200--}}
{{--                    </td>--}}

{{--                    <td valign="top p-0" colspan="1" class="dataTables_empty">--}}
{{--                        <button class="btn btn-secondary" disabled>--}}
{{--                            {{ __('base.withdrawn') }}--}}
{{--                        </button>--}}
{{--                    </td>--}}
{{--                </tr>--}}

{{--                <tr class="odd">--}}
{{--                    <td valign="top p-0" colspan="1" class="dataTables_empty">--}}
{{--                        18.03.2022--}}
{{--                    </td>--}}

{{--                    <td valign="top p-0" colspan="1" class="dataTables_empty">--}}
{{--                        18.03.2022--}}
{{--                    </td>--}}

{{--                    <td valign="top p-0" colspan="1" class="dataTables_empty">--}}
{{--                        299--}}
{{--                    </td>--}}

{{--                    <td valign="top p-0" colspan="1" class="dataTables_empty">--}}
{{--                        <button class="btn btn-success" disabled>--}}
{{--                            {{ __('base.unavailable') }}--}}
{{--                        </button>--}}
{{--                    </td>--}}
{{--                </tr>--}}
            {{--@endforeach--}}

    </tbody>

    <tfoot>
        <tr>
            <th rowspan="1" colspan="1">
                {{ __('payment.start') }}
            </th>

            <th rowspan="1" colspan="1">
                {{ __('base.withdrawal') }}
            </th>

            <th rowspan="1" colspan="1">
                {{ __('payment.total_amount') }}    
            </th>
            
            <th rowspan="1" colspan="1">
                {{ __('base.status') }}
            </th>
        </tr>
    </tfoot>
</table>
