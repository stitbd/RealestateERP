<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandPayment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function landSale(){
        return $this->belongsTo(LandSale::class, 'land_sale_id', 'id' );
    }

    public function PaymentType(){
        return $this->belongsTo(PaymentType::class, 'payment_type_id', 'id' );
    }

    public function fund(){
        return $this->belongsTo(Fund::class, 'fund_id', 'id' );
    }

    public function bank(){
        return $this->belongsTo(Bank::class, 'bank_id', 'id' );
    }

    public function account(){
        return $this->belongsTo(BankAccount::class, 'account_id', 'id' );
    }
    public function project(){
        return $this->belongsTo(Project::class, 'project_id', 'id' );
    }


}
