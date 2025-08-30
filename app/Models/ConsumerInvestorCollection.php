<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumerInvestorCollection extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'consumer_investment_collections';

    public function consumer(){
        return $this->belongsTo(Investment::class, 'consumer_investor_id', 'id');
    }

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
        return $this->belongsTo(BankAccount::class, 'account_id', 'id');
    }
    public function payment_type(){
        return $this->belongsTo(PaymentType::class, 'payment_type_id', 'id');
    }
    public function collected_missed_months(){
        return $this->hasMany(CollectMissedMonthConsumer::class, 'consumer_collection_id', 'id');
    }
}
