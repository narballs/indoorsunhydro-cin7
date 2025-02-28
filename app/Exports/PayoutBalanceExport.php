<?php

namespace App\Exports;

use App\Models\PayoutBalance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PayoutBalanceExport implements FromCollection, WithHeadings
{
    protected $id;
    protected $hide_radar;
    protected $hide_chargebacks;

    public function __construct($id, $hide_radar = false, $hide_chargebacks = false)
    {
        $this->id = $id;
        $this->hide_radar = $hide_radar;
        $this->hide_chargebacks = $hide_chargebacks;
    }

    public function collection()
    {
        return PayoutBalance::where('payout_id', $this->id)
            ->select(
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
            )
            ->whereRaw("description NOT LIKE ?", ['%Radar%'])
            ->whereRaw("description NOT LIKE ?", ['%Chargeback%']) // Exclude both terms
            ->get();
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

