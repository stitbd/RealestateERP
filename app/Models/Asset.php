<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function asset_group() {
        return $this->belongsTo(AssetGroup::class,'group_id');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');

    }
}
