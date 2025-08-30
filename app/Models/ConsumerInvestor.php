<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumerInvestor extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function investor_collection(){
        return $this->hasMany(ConsumerInvestorCollection::class );
    }
}
