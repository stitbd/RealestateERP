<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetGroup extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'asset_groups';

    public function asset_category() {
        return $this->belongsTo(AssetCategory::class,'category_id');
    }
}
