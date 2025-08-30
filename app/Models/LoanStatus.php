<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanStatus extends Model
{
    use HasFactory;
     public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
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
    public function loan_collection(){
        return $this->hasMany(LoanCollection::class, 'loan_id');
    }
    public function category(){
        return $this->belongsTo(AccountCategory::class, 'category_id', 'id' );
    }
    public function head(){
        return $this->belongsTo(AccountHead::class, 'head_id','id');
    }
}
