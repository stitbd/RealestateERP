<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetails extends Model
{
    use HasFactory;
    public function item(){
        return $this->belongsTo(Item::class, 'item_id', 'id' );
    }
    public function purchase(){
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id' );
    }
}
