<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetPurchaseDetails extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'purchase_asset_details';

    public function asset_purchase()
    {
        return $this->belongsTo(AssetPurchase::class,'purchase_asset_id');
    }
    public function asset()
    {
        return $this->belongsTo(Asset::class,'asset_id');
    }
    public function asset_category()
    {
        return $this->belongsTo(AssetCategory::class,'asset_category_id');
    }
}
