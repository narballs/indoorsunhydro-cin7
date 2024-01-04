<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactLogs extends Model
{
    use HasFactory;

    protected $fillable  = [
        'user_id' , 
        'action_by',
        'action',
        'description'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User' , 'user_id' , 'id');
    }
    public function adminuser() {
        return $this->belongsTo('App\Models\User' , 'action_by' , 'id');
    }
}