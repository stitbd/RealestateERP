<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function landSale(){
        return $this->belongsTo(LandSale::class, 'land_sale_id', 'id' );
    }


}
