<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }

    public function property(){
        return $this->belongsTo(Property::class, 'property_id', 'id' );
    }

    public function renter(){
        return $this->belongsTo(Renter::class, 'renter_id', 'id' );
    }

    public function floor(){
        return $this->belongsTo(Floor::class, 'floor_id', 'id' );
    }

    public function meter(){
        return $this->belongsTo(Meter::class, 'meter_id', 'id' );
    }
}
