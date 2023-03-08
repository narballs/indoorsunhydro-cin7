<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'user_id',
        'action',
        'user_notes'
    ];

    public function contact(){
        return $this->belongsTo('App\Models\Contact', 'contact_id', 'contact_id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User', 'id', 'user_id');
    }
}
