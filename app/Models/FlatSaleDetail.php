<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlatSaleDetail extends Model
{
    use HasFactory;

    protected $fillable = ['land_sale_id', 'flat_id', 'company_id'];

    public function landSale()
    {
        return $this->belongsTo(LandSale::class);
    }

    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

