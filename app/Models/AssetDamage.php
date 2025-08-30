<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDamage extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'damages';

    public function asset()
    {
        return $this->belongsTo(Asset::class,'asset_id');
    }
    public function asset_group()
    {
        return $this->belongsTo(AssetGroup::class,'asset_group_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function damageActivityLogs()
    {
        return $this->hasMany(DamageActivityLog::class,'damage_id');
    }
}
