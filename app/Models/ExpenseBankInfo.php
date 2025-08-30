<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseBankInfo extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'expense_bank_info';

    public function bank(){
        return $this->belongsTo(Bank::class,'bank_id');
    }
    public function bank_account(){
        return $this->belongsTo(BankAccount::class,'account_id');
    }
}
