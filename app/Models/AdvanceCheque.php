<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceCheque extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bank(){
        return $this->belongsTo(BanK::class,'bank_id','id');
    }
    public function account(){
        return $this->belongsTo(BankAccount::class,'account_id','id');
    }
}
