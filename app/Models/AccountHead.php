<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountHead extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function category(){
        
        return $this->belongsTo(AccountCategory::class,'category_id');
    }
    public function income_details(){
        
        return $this->belongsTo(IncomeDetails::class,'head_id');
    }
}
