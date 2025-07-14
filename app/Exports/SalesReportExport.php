<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SalesReportExport implements FromCollection, WithHeadings, WithEvents
{
    protected $data;
    protected $totalAmount;
    protected $totalPartialRefund;

    public function __construct($data)
    {
        $this->data = $data;
        $this->totalAmount = $data->sum('amount');
        $this->totalPartialRefund = $data->sum('partial_refund_amount');
    }

    public function collection()
    {
        return $this->data->map(function ($report) {
            return [
                'Order ID' => $report->order_id,
                'Stripe ID' => $report->stripe_id,
                'Amount' => $report->amount !== null ? '$' . number_format($report->amount, 2) : '-',
                'Partially Refund Amount' => $report->partial_refund_amount !== null
                    ? '$' . number_format($report->partial_refund_amount, 2)
                    : '-',
                'Customer Email' => $report->customer_email,
                'Status' => ucfirst(str_replace('_', ' ', $report->status)),
                'Refund Date' => optional($report->refund_date)->format('Y-m-d H:i:s'),
                'Payment Method' => $report->payment_method,
                'Transaction Date' => optional($report->transaction_date)->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Stripe ID',
            'Amount',
            'Partially Refund Amount',
            'Customer Email',
            'Status',
            'Refund Date',
            'Payment Method',
            'Transaction Date',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $rowCount = $this->data->count() + 2;

                $event->sheet->setCellValue("B{$rowCount}", 'Total:');
                $event->sheet->setCellValue("C{$rowCount}", '$' . number_format($this->totalAmount, 2));
                $event->sheet->setCellValue("D{$rowCount}", '$' . number_format($this->totalPartialRefund, 2));

                // Optional: Bold the total row
                $event->sheet->getStyle("B{$rowCount}:D{$rowCount}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F3F3'],
                    ],
                ]);
            },
        ];
    }
}
