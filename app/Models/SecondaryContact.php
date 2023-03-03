<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondaryContact extends Model
{
    use HasFactory;
    protected $fillable = [
        'secondary_id',
        'parent_id',
        'is_parent',
        'user_id',
        'company',
        'firstName',
        'lastName',
        'jobTitle',
        'email', 
        'mobile',
        'phone',
        'hashKey',
        'hashUsed'
    ];
       
        public function contact()
    {
        return $this->belongsTo('App\Models\Contact', 'parent_id', 'contact_id');
    }
}
