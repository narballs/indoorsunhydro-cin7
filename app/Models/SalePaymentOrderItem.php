<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePaymentOrderItem extends Model
{
    use HasFactory;

    protected $table = 'sale_payment_order_items';
    protected $fillable = [
        'sale_payment_id',
        'orderId',
        'createdDate',
        'transactionId',
        'parentId',
        'productId',
        'productOptionId',
        'integrationRef',
        'sort',
        'code',
        'name',
        'option1',
        'option2',
        'option3',
        'qty',
        'styleCode',
        'barcode',
        'sizeCodes',
        'lineComments',
        'unitCost',
        'unitPrice',
        'uomPrice',
        'discount',
        'uomQtyOrdered',
        'uomQtyShipped',
        'uomSize',
        'qtyShipped',
        'holdingQty',
        'accountCode',
    ];
}
