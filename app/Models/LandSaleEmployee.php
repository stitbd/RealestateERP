<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandSaleEmployee extends Model
{
    use HasFactory;
    public function userType(){
        return $this->belongsTo(UserType::class,'user_type_id', 'id' );
    }
    public function director(){
        return $this->belongsTo(LandSaleEmployee::class,'director_id', 'id' );
    }
    public function coordinator(){
        return $this->belongsTo(LandSaleEmployee::class,'coordinator_id', 'id' );
    }
    public function head(){
        return $this->belongsTo(AccountHead::class, 'head_id', 'id' );
    }
    public function category(){
        return $this->belongsTo(AccountCategory::class, 'category_id', 'id' );
    }
}
