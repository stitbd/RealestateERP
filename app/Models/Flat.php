<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function flat_floor(){
        return $this->belongsTo(FlatFloor::class, 'flat_floor_id', 'id' );
    }
}
