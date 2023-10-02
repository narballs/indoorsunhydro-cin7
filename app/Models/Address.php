<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = [
        'contact_id',
        'type',
        'first_name',
        'last_name',
        'company_name',
        'street_address',
        'address2',
        'city',
        'state',
        'postal_code',
        'phone',
    ];
}
