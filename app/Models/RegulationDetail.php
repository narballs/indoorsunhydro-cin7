<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegulationDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'contact_id',
        'seller_name',
        'seller_address',
        'purchaser_signature',
        'certificate_eligibility_1',
        'certificate_eligibility_2',
        'equipment_type',
        'purchaser_company_name',
        'title',
        'purchaser_address',
        'purchaser_phone',
        'purchase_date',
        'regulation_permit_number'
        
    ];
}
