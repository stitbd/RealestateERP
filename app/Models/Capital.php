<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capital extends Model
{
    use HasFactory;

    public function head(){
        return $this->belongsTo(AccountHead::class,'head_id','id');
    }
}
