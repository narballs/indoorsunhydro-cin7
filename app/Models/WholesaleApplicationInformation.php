<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesaleApplicationInformation extends Model
{
    use HasFactory;
    protected $table = 'wholesale_application_information';
    protected $fillable = [
        'company',
        'slug',
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'payable_name',
        'payable_phone',
        'payable_email',
        'permit_image',
        'parent_company'
    ];

    public function wholesale_application_address()
    {
        return $this->hasMany(WholesaleApplicationAddress::class , 'wholesale_application_id');
    }

    public function wholesale_application_regulation_detail()
    {
        return $this->hasOne(WholesaleApplicationRegulationDetail::class , 'wholesale_application_id');
    }

    public function wholesale_application_authorization_detail()
    {
        return $this->hasOne(WholesaleApplicationAuthorizationDetail::class , 'wholesale_application_id');
    }

    public function wholesale_application_card()
    {
        return $this->hasOne(WholesaleApplicationCard::class , 'wholesale_application_id');
    }
    public function permit_images()
    {
        return $this->hasMany(WholesaleApplicationImage::class , 'wholesale_application_id');
    }
}
