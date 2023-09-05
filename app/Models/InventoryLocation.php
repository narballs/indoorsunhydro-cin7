<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLocation extends Model
{
    use HasFactory;
    protected $table="inventory_locations";

    protected $fillable = [
        'cin7_branch_id',
        'branch_name',
        'status'
    ];
}
