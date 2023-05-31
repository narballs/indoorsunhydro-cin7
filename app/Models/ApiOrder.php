<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiOrder extends Model
{
    use HasFactory;
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
        'date',
        'po_number',
        'memo',
        'tax_class_id',
        'user_switch',
        'isVoid'
    ];
    public function contact()
    {
        return $this->belongsTo('App\Models\Contact', 'memberId', 'contact_id');
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
}
