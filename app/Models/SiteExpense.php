<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteExpense extends Model
{
    use HasFactory;

    public function project(){
        return $this->belongsTo(Project::class,'project_id','id');
    }

    public function head(){
        return $this->belongsTo(AccountCategory::class,'main_head_id','id');
    }

    public function sub_head(){
        return $this->belongsTo(AccountHead::class,'sub_head_id','id');
    }
}
