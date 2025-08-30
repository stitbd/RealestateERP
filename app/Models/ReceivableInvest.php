<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivableInvest extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'receivable_invest';
    
     public function fund(){
        return $this->belongsTo(Fund::class, 'fund_id', 'id' );
    }
    public function bank(){
        return $this->belongsTo(Bank::class, 'bank_id', 'id' );
    }
    public function invest(){
        return $this->belongsTo(Investment::class, 'invest_id', 'id' );
    }
    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }
}
