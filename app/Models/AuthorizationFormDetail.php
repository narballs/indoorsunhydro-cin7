<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorizationFormDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'contact_id',
        'authorize_name',
        'financial_institute_name',
        'financial_institute_address',
        'financial_institute_signature',
        'set_amount',
        'maximum_amount',
        'financial_institute_routine_number',
        'financial_institute_account_number',
        'financial_institute_permit_number',
        'financial_institute_phone_number',
        
    ];
}
