<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diposit extends Model
{
    use HasFactory;

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }

    public function fund(){
        return $this->belongsTo(Fund::class, 'fund_id', 'id' );
    }
    
    public function diposit_details(){
        return $this->belongsTo(DipositDetails::class, 'id', 'diposit_id' );
    }
}
