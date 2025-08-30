<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandPurchase extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function projectInformation()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }
    public function landPurchaseDetails()
    {
        return $this->hasMany(LandPurchaseDetail::class);
    }
}
