<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiOrder extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'order_id',
        'createdDate',
        'modifiedDate',
        'createdBy',
        'processedBy',
        'isApproved',
        'reference',
        'memberId',
        'branchId',
        'branchEmail',
        'productTotal',
        'primaryId',
        'secondaryId',
        'total',
        'apiApproval',
        'currencyCode',
        'currencyRate',
        'currencySymbol',
        'status',
        'stage',
        'paymentTerms',
        'logisticsCarrier',
        'date',
        'po_number',
        'memo',
        'tax_class_id',
        'user_switch',
        'isVoid',
        'total_including_tax',
        'shipment_price',
        'payment_status',
        'shipstation_orderId',
        'label_created',
        'is_shipped',
        'is_stripe',
        'label_link',
        'order_status_id',
        'is_default',
        'discount_id',
        'discount_amount',
        'tax_rate',
        'shipping_carrier_code',
        'shipping_service_code',
        'parcel_guard',
        'internal_comments',
        'delievery_instructions',
        'DeliveryFirstName',
        'DeliveryLastName',
        'DeliveryCompany',
        'DeliveryAddress1',
        'DeliveryAddress2',
        'DeliveryCity',
        'DeliveryState',
        'DeliveryZip',
        'DeliveryCountry',
        'DeliveryPhone',
        'BillingFirstName',
        'BillingLastName',
        'BillingCompany',
        'BillingAddress1',
        'BillingAddress2',
        'BillingCity',
        'BillingState',
        'BillingZip',
        'BillingCountry',
        'BillingPhone',
        'tracking_number',
        'shipstation_orderKey',
        
    ];
    public function contact()
    {
        return $this->belongsTo('App\Models\Contact', 'memberId', 'contact_id');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function createdby()
    {
        return $this->belongsTo('App\Models\ApiUser', 'createdBy', 'user_id');
    }
    public function secondary_contact()
    {
        return $this->belongsTo('App\Models\Contact', 'secondaryId', 'secondary_id');
    }

    public function primary_contact()
    {
        return $this->belongsTo('App\Models\Contact', 'primaryId', 'contact_id');
    }

    public function processedby()
    {
        return $this->belongsTo('App\Models\ApiUser', 'processedBy', 'user_id');
    }

    public function orderItem()
    {
        return $this->hasMany('App\Models\ApiOrderItem', 'id', 'product_id');
    }
    public function apiOrderItem()
    {
        return $this->hasMany('App\Models\ApiOrderItem', 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function texClasses()
    {
        return $this->belongsTo('App\Models\TaxClass', 'tax_class_id', 'id');
    }
    public function discount()
    {
        return $this->belongsTo('App\Models\Discount', 'discount_id', 'id');
    }

    Public function order_refund()
    {
        return $this->hasMany('App\Models\OrderRefund', 'order_id', 'id');
    }
}