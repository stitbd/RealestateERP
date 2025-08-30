<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPayment extends Model
{
    use HasFactory;
    public function payment_details(){
        return $this->belongsTo(SalesPaymentDetails::class, 'id', 'payment_id' );
    }

    public function fund(){
        return $this->belongsTo(Fund::class, 'fund_id', 'id' );
    }

    public function bank(){
        return $this->belongsTo(Bank::class, 'bank_id', 'id' );
    }

    public function account(){
        return $this->belongsTo(BankAccount::class, 'bank_account_no', 'id' );
    }
}
