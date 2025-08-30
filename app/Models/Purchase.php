<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }
    public function project(){
        return $this->belongsTo(Project::class, 'project_id', 'id' );
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id' );
    }
    public function purchase_details(){
        return $this->hasMany(PurchaseDetails::class );
    }
}
