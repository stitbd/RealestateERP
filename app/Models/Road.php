<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Road extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'road';

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }
    // public function project(){
    //     return $this->belongsTo(Project::class, 'project_id', 'id' );
    // }
    public function sector(){
        return $this->belongsTo(Sector::class, 'sector_id', 'id' );
    }
}
