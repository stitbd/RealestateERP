<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevelopmentPayment extends Model
{
    protected $fillable = [
        'land_sale_id', 'land_payment_id', 'amount',
        'start_date', 'end_date', 'image', 'note'
    ];

    public function land_sale()
    {
        return $this->belongsTo(LandSale::class);
    }

    public function land_payment()
    {
        return $this->belongsTo(LandPayment::class);
    }
}
