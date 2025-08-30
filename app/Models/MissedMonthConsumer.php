<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissedMonthConsumer extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'missed_months';

    public function missed_months(){
        return $this->belongsTo(ConsumerInvestorCollection::class, 'consumer_collection_id', 'id');
    }
}
