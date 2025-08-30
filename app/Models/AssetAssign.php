<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetAssign extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'asset_assign';

    public function asset()
    {
        return $this->belongsTo(Asset::class,'asset_id');
    }
    public function asset_group()
    {
        return $this->belongsTo(AssetGroup::class,'asset_group_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }
}
