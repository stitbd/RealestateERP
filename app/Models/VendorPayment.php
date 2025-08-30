<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPayment extends Model
{
    use HasFactory;

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }
    public function project(){
        return $this->belongsTo(Project::class, 'project_id', 'id' );
    }
    public function vendor(){
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id' );
    }
    public function fund(){
        return $this->belongsTo(Fund::class, 'fund_id', 'id' );
    }
    public function payment_details(){
        return $this->belongsTo(VendorPaymentDetails::class, 'id', 'vendor_payment_id' );
    }
}
