<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'customers';


    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }
    public function road(){
        return $this->belongsTo(Road::class, 'road_id', 'id' );
    }
    public function sector(){
        return $this->belongsTo(Sector::class, 'sector_id', 'id' );
    }
    public function plot(){
        return $this->belongsTo(Plot::class, 'plot_id', 'id' );
    }
    public function project(){
        return $this->belongsTo(Project::class, 'project_id', 'id' );
    }

    // public function landSale(){
    //     return $this->hasOne(LandSale::class, 'customer_id' );

    // }

    public function landSales()
{
    return $this->belongsToMany(LandSale::class, 'customer_land_sales');
}


public function landSale()
{
    return $this->belongsTo(LandSale::class);
}



}
