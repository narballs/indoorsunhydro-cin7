<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvalidAddressUser extends Model
{
    use HasFactory;

    protected $table = 'invalid_address_users';
    protected $fillable = [
        'email',
        'firstName',
        'lastName',
        'billing_address_1',
        'billing_address_2',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        'billing_country',
        'shipping_address_1',
        'shipping_address_2',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        'summary_sent'
    ];
}
