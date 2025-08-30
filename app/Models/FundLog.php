<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundLog extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }
    public function sapplier_payment(){
        return $this->belongsTo(Payment::class, 'transection_id', 'id' );
    }
    public function vendor_payment(){
        return $this->belongsTo(VendorPayment::class, 'transection_id', 'id' );
    }
    public function salary_payment(){
        return $this->belongsTo(EmployeeSalaryPayment::class, 'transection_id', 'id' );
    }
    public function requisition_payment(){
        return $this->belongsTo(RequisitionPayment::class, 'transection_id', 'id' );
    }
    public function diposit(){
        return $this->belongsTo(Diposit::class, 'transection_id', 'id' );
    }
    public function expense(){
        return $this->belongsTo(Expense::class, 'transection_id', 'id' );
    }
    public function fund(){
        return $this->belongsTo(Fund::class, 'fund_id', 'id' );
    }
}
