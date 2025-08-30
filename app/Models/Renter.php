<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renter extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }

    public function property(){
        return $this->belongsTo(Property::class, 'property_id', 'id' );
    }

    public function unit(){
        return $this->belongsTo(Unit::class, 'unit_id', 'id' );
    }

    public function floor(){
        return $this->belongsTo(Floor::class, 'floor_id', 'id' );
    }
}
