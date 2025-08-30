<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionPayment extends Model
{
    use HasFactory;
    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }
    public function project(){
        return $this->belongsTo(Project::class, 'project_id', 'id' );
    }
    public function fund(){
        return $this->belongsTo(Fund::class, 'fund_id', 'id' );
    }
    public function details(){
        return $this->hasOne(RequisitionPaymentDetails::class, 'payment_id', 'id' );
    }
}
