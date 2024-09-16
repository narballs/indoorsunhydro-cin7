<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactsAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
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
        'address_type',
        'is_default',
        'address_label',
    ];


    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
