<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory , SoftDeletes;
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
        'notes',
        'hashKey',
        'hashUsed',
        'credit_limit',
        'balance_owing',
        'tax_class',
        'paymentTerms',
        'is_deleted',
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

    public function children()
    {
        return $this->belongsTo('App\Models\Contact', 'conatct_id','parent_id');
    }

    public function parent()
    {
        return $this->hasMany('App\Models\Contact', 'contact_id', 'parent_id');
    }
    public function customerDiscount()
    {
        return $this->hasOne(CustomerDiscount::class);
    }
    public function allow_user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
