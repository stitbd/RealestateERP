<?php

namespace App\Models;

use App\Models\ItemCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }

    public function category(){
        return $this->belongsTo(ItemCategory::class, 'category_id', 'id' );
    }

    public function stock(){
        return $this->hasOne(Stock::class, 'item_id', 'id' );
    }
}
