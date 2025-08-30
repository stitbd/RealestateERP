<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageActivityLog extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'damage_logs';

    public function damage()
    {
        return $this->belongsTo(AssetDamage::class,'damage_id');
    }
}
