<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }
    public function project(){
        return $this->belongsTo(Project::class, 'project_id', 'id' );
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id' );
    }
    public function fund(){
        return $this->belongsTo(Fund::class, 'fund_id', 'id' );
    }
    public function category(){
        return $this->belongsTo(AccountCategory::class, 'category_id', 'id' );
    }
    public function head(){
        return $this->belongsTo(AccountHead::class, 'head_id', 'id' );
    }
    public function payment_details(){
        return $this->belongsTo(PaymentDetails::class, 'id', 'payment_id' );
    }

    public function order(){
        return $this->belongsTo(WorkOrder::class,'work_order_id','id');
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id','id');
    }
    
        public function payments()
{
    return $this->hasMany(LandPayment::class, 'land_sale_id');
}

}
