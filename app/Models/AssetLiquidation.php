<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetLiquidation extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'asset_liquidation';

    public function asset()
    {
        return $this->belongsTo(Asset::class,'asset_id');
    }

    public function asset_group()
    {
        return $this->belongsTo(AssetGroup::class,'asset_group_id');
    }

    public function fund()
    {
        return $this->belongsTo(Fund::class,'fund_id','id');
    }
    public function bank()
    {
        return $this->belongsTo(Bank::class,'bank_id','id');
    }
    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }
    public function account(){
        return $this->belongsTo(BankAccount::class, 'account_id', 'id' );
    }

    public function activityLogs()
    {
        return $this->hasMany(LiquidationActivityLog::class,'liquidation_id');
    }
}
