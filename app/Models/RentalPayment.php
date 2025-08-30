<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalPayment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function renter(){
        return $this->belongsTo(Renter::class,'renter_id','id');
    }

    public function bill(){
        return $this->belongsTo(RentalBill::class,'bill_id','id');
    }

    public function company(){
        return $this->belongsTo(Company::class,'company_id','id');
    }
}
