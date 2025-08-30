<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetStock extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function asset()
    {
        return $this->belongsTo(Asset::class,'asset_id','id');
    }

    public function group()
    {
        return $this->belongsTo(AssetGroup::class,'asset_group_id','id');
    }

}
