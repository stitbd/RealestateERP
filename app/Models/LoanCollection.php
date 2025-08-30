<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanCollection extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'loan_collections';

    public function loan(){
        return $this->belongsTo(LoanStatus::class, 'loan_id', 'id' );
    }

    public function receivable(){
        return $this->belongsTo(ReceivableLoan::class, 'receivable_id', 'id' );
    }

    public function fund(){
        return $this->belongsTo(Fund::class, 'fund_id', 'id' );
    }
    public function bank(){
        return $this->belongsTo(Bank::class, 'bank_id', 'id' );
    }
}
