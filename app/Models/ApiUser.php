<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'firstName',
        'lastName',
        'email',
        'jobTitle',
        'isActive'
    ];

    public function apiorders()
    {
        return $this->hasMany('App\Models\ApiOrder','createdBy','user_id');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\ApiOrder','processedBy','user_id');
    }
    //  public function apiorders2()
    // {
    //     return $this->hasMany('App\Models\ApiOrder','processedBy','user_id');
    // }
}
