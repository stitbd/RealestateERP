<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectMissedMonthConsumer extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'consumer_collect_missed_months';

    public function collection(){
        return $this->belongsTo(ConsumerInvestorCollection::class, 'consumer_collection_id', 'id');
    }

    public function consumer(){
        return $this->belongsTo(ConsumerInvestor::class, 'consumer_investor_id', 'id');
    }
}
