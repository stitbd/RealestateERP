<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadOpeningBalance extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }

    public function category(){
        return $this->belongsTo(AccountCategory::class, 'category_id', 'id' );
    }
    
    public function head(){
        return $this->belongsTo(AccountHead::class, 'head_id', 'id' );
    }
}
