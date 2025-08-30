<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapitalTransfer extends Model
{
    use HasFactory;

    protected $fillable = ['from_head_id','to_head_id','from_capital_id','to_capital_id','amount','date','remarks','attachment','created_by','updated_by'];

    public function fromHead(){
        return $this->belongsTo(AccountHead::class,'from_head_id','id');
    }

    public function toHead(){
        return $this->belongsTo(AccountHead::class,'to_head_id','id');
    }
}
