<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomCompanyRole extends Model
{
    protected $table = 'custom_company_roles';
    protected $fillable = [
        'role_id',
    ];
}
