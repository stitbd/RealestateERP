<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;
    protected $guarded = [];  
    public function bank(){
        return $this->belongsTo(Bank::class,'bank_id','id');
    }
}
