<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePayments extends Model
{
    use HasFactory;

    protected $table = 'sale_payments';
    protected $fillable = [
        'sale_payment_id',
        'createdDate',
        'modifiedDate',
        'paymentDate',
        'amount',
        'method',
        'isAuthorized',
        'transactionRef',
        'comments',
        'orderId',
        'orderRef',
        'paymentImportedRef',
        'batchReference',
        'reconcileDate',
        'branchId',
        'orderType',
        'customer_first_name',
        'customer_last_name',
        'invoice_number',
        'po_number',
        'company',
        'email'
    ];
}
