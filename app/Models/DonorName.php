<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorName extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function landPurchaseDetails(){
        return $this->belongsTo(LandPurchaseDetail::class, 'land_purchase_detail_id', 'id');

    }
}
