<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRevoke extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'revoke_assets';

    public function asset()
    {
        return $this->belongsTo(Asset::class,'asset_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }
    
}
