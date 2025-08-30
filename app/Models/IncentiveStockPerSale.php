<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class IncentiveStockPerSale extends Model
{
    use HasFactory;
    protected $guarded = [];

    // public function incentive(){
    //     return $this->belongsTo(SalesIncentive::class, 'sales_incentive_id', 'id' );
    // }

    public function employee(){
        return $this->belongsTo(LandSaleEmployee::class, 'land_sale_employee_id', 'id' );
    }

    public function head(){
        return $this->belongsTo(AccountHead::class, 'head_id', 'id' );
    }
    
    public function landSale()
{
    return $this->belongsTo(\App\Models\LandSale::class, 'land_sale_id'); // Adjust the foreign key if it's different
}


}
