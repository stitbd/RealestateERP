<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plot extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }
    public function road(){
        return $this->belongsTo(Road::class, 'road_id', 'id' );
    }
    public function sector(){
        return $this->belongsTo(Sector::class, 'sector_id', 'id' );
    }
    public function plotType(){
        return $this->belongsTo(PlotType::class, 'type_id', 'id' );
    }
    public function project(){
        return $this->belongsTo(Project::class, 'project_id', 'id' );
    }
}
