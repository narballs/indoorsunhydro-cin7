<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class DefaultShippingMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'shipping_quote_setting_id',
    ];
    public function shipping_quote_setting()
    {
        return $this->belongsTo(ShippingQuoteSetting::class);
    }
}