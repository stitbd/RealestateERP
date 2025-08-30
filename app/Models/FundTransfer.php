<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundTransfer extends Model
{
    use HasFactory;
    public function capital(){
        return $this->belongsTo(Capital::class,'capital_id');
    }
    public function from_fund(){
        return $this->belongsTo(FundCurrentBalance::class,'from_fund_id','fund_id');
    }
    public function from_bank(){
        return $this->belongsTo(Bank::class,'from_bank_id');
    }
    public function from_account(){
        return $this->belongsTo(BankAccount::class,'from_acc_no');
    }
    public function to_fund(){
        return $this->belongsTo(FundCurrentBalance::class,'to_fund_id','fund_id');
    }
    public function to_bank(){
        return $this->belongsTo(Bank::class,'to_bank_id','id');
    }
    public function to_acc(){
        return $this->belongsTo(BankAccount::class,'to_acc_no');
    }
    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }
}
