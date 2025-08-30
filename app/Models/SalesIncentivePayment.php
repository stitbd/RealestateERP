<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesIncentivePayment extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'sales_incentives_payments';

    public function sales_incentive()
    {
        return $this->belongsTo(IncentiveStockPerSale::class, 'incentive_stock_per_sales_id', 'id');
    }
}
