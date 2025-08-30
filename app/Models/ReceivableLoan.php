<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivableLoan extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'tbl_receivable';
    
     public function fund(){
        return $this->belongsTo(Fund::class, 'fund_id', 'id' );
    }
    public function bank(){
        return $this->belongsTo(Bank::class, 'bank_id', 'id' );
    }
    public function loan(){
        return $this->belongsTo(LoanStatus::class, 'loan_id', 'id' );
    }

    public function loancollection(){
        return $this->hasMany(LoanCollection::class, 'receivable_id', 'id' );
    }
    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }
}
