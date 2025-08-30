<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetPurchase extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'purchase_asset';

    public function asset_details()
    {
        return $this->hasMany(AssetPurchaseDetails::class,'purchase_asset_id');
    }
    public function asset()
    {
        return $this->belongsTo(Asset::class);
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
}
