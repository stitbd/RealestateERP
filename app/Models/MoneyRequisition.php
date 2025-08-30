<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyRequisition extends Model
{
    use HasFactory;
    
    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }

    public function project(){
        return $this->belongsTo(Project::class, 'project_id', 'id' );
    }
    
    public function approved_user() {
        return $this->belongsTo(User::class, 'approved_by', 'id' );
    }
}
