<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlotSaleDetail extends Model
{
    use HasFactory;

    protected $fillable = ['land_sale_id', 'plot_id', 'company_id'];

    public function landSale()
    {
        return $this->belongsTo(LandSale::class);
    }

    public function plot()
    {
        return $this->belongsTo(Plot::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
