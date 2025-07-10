<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Stripe ID</th>
            <th>Amount</th>
            <th>Partially Refund Amount</th>
            <th>Customer Email</th>
            <th>Status</th>
            <th>Payment Method</th>
            <th>Refund Date</th>
            <th>Transaction Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $report)
            <tr>
                <td>{{ $report->order_id }}</td>
                <td>{{ $report->stripe_id }}</td>
                <td>{{ $report->amount ? '$'.$report->amount : '' }}</td>
                <td>{{ $report->partial_refund_amount ? '$'.$report->partial_refund_amount : '' }}</td>
                <td>{{ $report->customer_email }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $report->status)) }}</td>
                <td>{{ $report->payment_method }}</td>
                <td>{{ optional($report->refund_date)->format('Y-m-d') }}</td>
                <td>{{ optional($report->transaction_date)->format('Y-m-d') }}</td>
            </tr>
        @endforeach
        @if ($data->count())
            <tr style="font-weight:bold;">
                <td colspan="2" align="right">Total:</td>
                <td>{{$totalAmount ?  '$'. number_format($totalAmount, 2) : 0.00 }}</td>
                <td>{{ $totalPartialRefund ? '$'.number_format($totalPartialRefund, 2) : 0.00 }}</td>
                <td colspan="5"></td>
            </tr>
        @endif
    </tbody>
</table>
