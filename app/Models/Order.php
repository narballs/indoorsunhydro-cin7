<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ["contact_id","status","api_status","api_ref_no","first_name","last_name","company","country","street_address", "street_address_2", "town_city", "state", "zip", "phone", "email"];
    public function orderItem()
    {
        return $this->hasMany('App\Models\OrderItem', 'order_id', 'id');
    }

    public function orderComment()
    {
        return $this->hasMany('App\Models\OrderComment', 'order_id', 'id');
    }

    public function status()
    {
        return $this->hasOne('App\Models\OrderStatus')->ofMany('status');
    }

    public function contact()
    {
        return $this->belongsTo('App\Models\Contact', 'contact_id', 'id');
    }

   
    // public function contact()
    // {
    //     return $this->hasOne('App\Models\Contact')->ofMany('contact');
    // }
}
