<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesaleApplicationImage extends Model
{
    use HasFactory;
    protected $table = 'wholesale_application_images';
    protected $fillable = [
        'wholesale_application_id',
        'permit_image',
    ];

    public function wholesale_application()
    {
        return $this->belongsTo(WholesaleApplicationInformation::class , 'wholesale_application_id' , 'id');
    }
}
