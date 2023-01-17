<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    // protected $hidden = ['id','contact_id'];
    protected $fillable = [
        'id',
        'contact_id',
        'status',
        'type',
        'priceColumn',
        'company',
        'firstName',
        'lastName',
        'jobTitle',
        'mobile',
        'phone',
        'address1',
        'address2',
        'city',
        'postCode',
        'delivery_city',
        'state',
        'postalAddress1',
        'postalAddress2',
        'postalCity',
        'postalState',
        'postalPostCode',
        'fax',
        'website',
        'email',
        'notes'
    ];

    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'contact_id', 'id');
    }

    public function apiorders()
    {
        return $this->hasMany('App\Models\ApiOrder', 'contact_id', 'memberId');
    }
}