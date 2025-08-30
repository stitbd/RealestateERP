<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }
    
    public function project(){
        return $this->belongsTo(Project::class, 'project_id', 'id' );
    }

    public function item(){
        return $this->belongsTo(Item::class, 'item_id', 'id' );
    }
}
