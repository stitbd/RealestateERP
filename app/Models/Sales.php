<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }
    public function project(){
        return $this->belongsTo(Project::class, 'project_id', 'id' );
    }
    public function sales_details(){
        return $this->hasMany(SalesDetails::class, 'sales_id', 'id' );
    }
    public function sales_payment(){
        return $this->hasMany(SalesPayment::class, 'sales_id', 'id' );
    }
}
