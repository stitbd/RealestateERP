<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'work_orders';

    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id' );
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }


}
