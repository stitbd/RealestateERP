<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalBill extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function unit(){
        return $this->belongsTo(Unit::class, 'unit_id', 'id' );
    }

    public function renter(){
        return $this->belongsTo(Renter::class, 'renter_id', 'id' );
    }

    public function company(){
        return $this->belongsTo(Company::class,'company_id','id');
    }

    
}
