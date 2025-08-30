<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnInvest extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'return_invests';

    public function invest(){
        return $this->belongsTo(Investment::class, 'invest_id', 'id' );
    }

    public function receivable(){
        return $this->belongsTo(ReceivableInvest::class, 'receivable_invest_id', 'id' );
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

}
