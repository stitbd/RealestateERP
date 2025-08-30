<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetails extends Model
{
    use HasFactory;

    public function bank(){
        return $this->belongsTo(Bank::class,'bank_id','id');

    }
    public function account(){
        return $this->belongsTo(BankAccount::class,'account_id','id');

    }

}
