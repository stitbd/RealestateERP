<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
     use HasFactory;
    protected $guarded = [];
    protected $table = 'consumer_investors';

    public function fund(){
        return $this->belongsTo(Fund::class, 'fund_id', 'id' );
    }
    public function bank(){
        return $this->belongsTo(Bank::class, 'bank_id', 'id' );
    }
    public function account(){
        return $this->belongsTo(BankAccount::class, 'account_id', 'id' );
    }
    // public function return_invest(){
    //     return $this->hasMany(ReturnInvest::class, 'invest_id');
    // }
    public function collect_invest(){
        return $this->hasMany(ConsumerInvestorCollection::class, 'consumer_investor_id');
    }
    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }
    public function category(){
        return $this->belongsTo(AccountCategory::class, 'category_id', 'id' );
    }
    public function head(){
        return $this->belongsTo(AccountHead::class, 'head_id','id');
    }
    
}
