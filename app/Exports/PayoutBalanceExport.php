<?php

namespace App\Exports;

use App\Models\PayoutBalance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PayoutBalanceExport implements FromCollection, WithHeadings
{
    protected $payoutId;

    public function __construct($payoutId)
    {
        $this->payoutId = $payoutId;
    }

    public function collection()
    {
        return PayoutBalance::where('payout_id', $this->payoutId)
            ->select(
                // 'payout_balance_id',
                'order_id',
                'customer_name',
                'customer_email',
                'currency',
                'type',
                'description',
                'amount',
                'converted_amount',
                'fees',
                'net',
                'charge_created'
            )->get();
    }

    public function headings(): array
    {
        return [
            // 'Payout Balance ID',
            'Order ID',
            'Customer Name',
            'Customer Email',
            'Currency',
            'Type',
            'Description',
            'Amount',
            'Converted Amount',
            'Fees',
            'Net',
            'Charge Created',
        ];
    }
}

