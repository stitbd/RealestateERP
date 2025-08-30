<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandPurchaseDetail extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function donors(){
        return $this->hasMany(DonorName::class, 'land_purchase_detail_id', 'id');

    }

    public function documents(){
        return $this->hasMany(LandDocumet::class, 'land_purchase_detail_id', 'id');

    }
}
