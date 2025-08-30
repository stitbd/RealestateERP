<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandSale extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'land_sales';

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }

    public function project(){
        return $this->belongsTo(Project::class, 'project_id', 'id' );
    }
    public function plot(){
        return $this->belongsTo(Plot::class, 'plot_id', 'id' );
    }

    public function flat(){
        return $this->belongsTo(Flat::class, 'flat_id', 'id' );
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id' );
    }

    public function land_payments()
    {
        return $this->hasMany(LandPayment::class,'land_sale_id');
    }

    public function incentive()
    {
        return $this->hasMany(SalesIncentive::class,'land_sale_id');
    }

    public function director(){
        return $this->belongsTo(LandSaleEmployee::class,'director_id', 'id' );
    }

    public function installment()
    {
        return $this->belongsTo(Installment::class,'land_sale_id','id');
    }

    public function salesIncentives()
    {
        return $this->hasMany(SalesIncentive::class);
    }

    public function landSale()
{
    return $this->belongsTo(LandSale::class, 'land_sale_id');
}



public function flatSaleDetails()
{
    return $this->hasMany(FlatSaleDetail::class);
}

public function plotSaleDetails()
{
    return $this->hasMany(PlotSaleDetail::class);
}

public function flats()
{
    return $this->hasManyThrough(Flat::class, FlatSaleDetail::class, 'land_sale_id', 'id', 'id', 'flat_id');
}

public function plots()
{
    return $this->hasManyThrough(Plot::class, PlotSaleDetail::class, 'land_sale_id', 'id', 'id', 'plot_id');
}


// public function customers()
// {
//     return $this->belongsToMany(Customer::class, 'customer_land_sales');
// }

public function primaryCustomer()
{
    return $this->belongsTo(Customer::class, 'customer_id');
}




public function mainCustomer()
{
    return $this->belongsTo(Customer::class, 'customer_id');
}


public function landPayments()
{
    return $this->hasMany(LandPayment::class);
}

public function customers()
{
    return $this->hasMany(Customer::class);
}




public function landSaleDetails()
{
    return $this->hasMany(LandSaleDetail::class);
}

public function landshares()
{
    return $this->belongsToMany(Landshare::class, 'land_sale_details');
}







}
