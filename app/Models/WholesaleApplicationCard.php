<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesaleApplicationCard extends Model
{
    use HasFactory;
    protected $table = 'wholesale_application_cards';
    protected $fillable = [
        'wholesale_application_id',
        'card_type',
        'card_number',
        'cardholder_name',
        'expiration_date',
        'cardholder_zip_code',
        'authorize_card_name',
        'date',
        'customer_signature',
        'authorize_card_text',
        



    ];

    public function wholesale_application()
    {
        return $this->belongsTo(WholesaleApplicationInformation::class , 'wholesale_application_id' , 'id');
    }
}
