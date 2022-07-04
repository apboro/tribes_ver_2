<table
        class="dt-complex-header table table-bordered table-responsive"
        id="DataTables_Table_1"
        role="grid"
        aria-describedby="DataTables_Table_1_info"
>
    <thead>
    <tr role="row">
        <th
                class="sorting sorting_asc"
                tabindex="0"
                aria-controls="DataTables_Table_1"
                rowspan="1"
                colspan="1"
                aria-sort="ascending"
                aria-label="Name: activate to sort column descending"
        >{{ __('payment.pay_id') }}</th>

        <th
                class="sorting"
                tabindex="0"
                aria-controls="DataTables_Table_1"
                rowspan="1"
                colspan="1"
                aria-label="Email: activate to sort column ascending"
        >{{ __('payment.payment_date') }}</th>

        <th
                class="sorting"
                tabindex="0"
                aria-controls="DataTables_Table_1"
                rowspan="1"
                colspan="1"
                aria-label="Post: activate to sort column ascending"
        >{{ __('base.user') }}</th>

        <th
                class="sorting"
                tabindex="0"
                aria-controls="DataTables_Table_1"
                rowspan="1"
                colspan="1"
                aria-label="City: activate to sort column ascending"
        >{{ __('payment.payment_type') }}</th>

        <th
                class="sorting"
                tabindex="0"
                aria-controls="DataTables_Table_1"
                rowspan="1"
                colspan="1"
                aria-label="Date: activate to sort column ascending"
        >{{ __('base.community') }}</th>
        <th
                class="sorting"
                tabindex="0"
                aria-controls="DataTables_Table_1"
                rowspan="1"
                colspan="1"
                aria-label="Date: activate to sort column ascending"
        >Статус</th>

        <th
                class="sorting"
                tabindex="0"
                aria-controls="DataTables_Table_1"
                rowspan="1"
                colspan="1"
                aria-label="Salary: activate to sort column ascending"
        >
            {{ __('base.amount') }}
        </th>
    </tr>
    </thead>

    <tbody>
        @if(count($payments) == 0)
            <tr class="odd">
                <td valign="top" colspan="5" class="dataTables_empty">
                    Записи отсутствуют
                </td>
            </tr>
        @else

        @foreach($payments as $payment)

            <tr class="odd" style="@if($payment->type == 'payout') background: #f9300217; @else background: #49ab2917; @endif">
                <td valign="top" colspan="1" class="dataTables_empty">
                    {{ $payment->OrderId }}
                </td>

                <td valign="top" colspan="1" class="dataTables_empty">
                    {{ $payment->created_at->format('d.m.Y H:i:s') }}
                </td>

                <td valign="top" colspan="1" class="dataTables_empty">
                    {{ $payment->from }}
                </td>

                <td valign="top" colspan="1" class="dataTables_empty">
                    {{ $payment->getType() }}
                </td>

                <td valign="top" colspan="1" class="dataTables_empty">
                    {{ $payment->type == 'payout' ? 'Все' : ($payment->community()->first()->title ?? "-") }}
                </td>

                <td valign="top" colspan="1" class="dataTables_empty">
                    {{ \App\Models\Payment::$status[$payment->status] ?? 'Статус не определен' }}
                </td>

                <td valign="top" colspan="1" class="dataTables_empty">
                    {{ $payment->formattedAmount() }}
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>

    <tfoot>
        <tr>
            <th rowspan="1" colspan="1">{{ __('payment.pay_id') }}</th>
            <th rowspan="1" colspan="1">{{ __('payment.payment_date') }}</th>
            <th rowspan="1" colspan="1">{{ __('base.user') }}</th>
            <th rowspan="1" colspan="1">{{ __('payment.payment_type') }}</th>
            <th rowspan="1" colspan="1">{{ __('base.community') }}</th>
            <th rowspan="1" colspan="1">Статус</th>
            <th rowspan="1" colspan="1">{{ __('base.amount') }}</th>
        </tr>
    </tfoot>
</table>
