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
        'secondary_id',
        'parent_id',
        'is_parent',
        'status',
        'type',
        'user_id',
        'priceColumn',
        'company',
        'firstName',
        'lastName',
        'jobTitle',
        'mobile',
        'phone',
        'address1',
        'address2',
        'city_id',
        'postCode',
        'delivery_city',
        'state_id',
        'postalAddress1',
        'postalAddress2',
        'postalCity',
        'postalState',
        'postalPostCode',
        'fax',
        'website',
        'email',
        'notes',
        'hashKey',
        'hashUsed'
    ];

    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'contact_id', 'id');
    }

    public function apiorders()
    {
        return $this->hasMany('App\Models\ApiOrder', 'contact_id', 'memberId');
    }

    public function states()
    {
        return $this->hasMany('App\Models\UsState', 'id', 'state_id');
    }

    public function cities()
    {
        return $this->hasMany('App\Models\UsCity', 'id', 'city_id');
    }

    public function secondary_contact()
    {
        return $this->hasMany('App\Models\SecondaryContact', 'parent_id', 'contact_id')->orderBy('id', 'desc');
    }
}
