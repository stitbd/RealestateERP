<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SalesIncentive extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function director(){
        return $this->belongsTo(LandSaleEmployee::class,'director_id', 'id' );
    }

    public function co_ordinator(){
        return $this->belongsTo(LandSaleEmployee::class,'coordinator_id', 'id' );
    }

    public function shareholder(){
        return $this->belongsTo(LandSaleEmployee::class,'shareholder_id', 'id' );
    }

    public function outsider(){
        return $this->belongsTo(LandSaleEmployee::class,'outsider_id', 'id' );
    }

    public function land_sale(){
        return $this->belongsTo(LandSale::class,'land_sale_id', 'id' );
    }

    public function incentive_per_sale()
    {
        return $this->hasMany(IncentiveStockPerSale::class,'sales_incentive_id');
    }
}
