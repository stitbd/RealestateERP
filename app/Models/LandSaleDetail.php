<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandSaleDetail extends Model
{
    protected $fillable = ['land_sale_id', 'landshare_id', 'company_id'];

    public function landshare()
    {
        return $this->belongsTo(Landshare::class);
    }

    public function landSale()
    {
        return $this->belongsTo(LandSale::class);
    }
}
