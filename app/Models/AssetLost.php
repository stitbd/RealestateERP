<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetLost extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'asset_lost';
    
    public function asset()
    {
        return $this->belongsTo(Asset::class,'asset_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function asset_group()
    {
        return $this->belongsTo(AssetGroup::class,'asset_group_id');
    }

    public function lostActivityLogs()
    {
        return $this->hasMany(LostActivityLog::class,'lost_id');
    }
}
