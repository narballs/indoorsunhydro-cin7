<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationalZipCode extends Model
{
    use HasFactory;
    protected $table="operational_zip_codes";

    protected $fillable = [
        'zip_code',
        'status'
    ];
}
