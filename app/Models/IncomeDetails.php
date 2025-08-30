<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeDetails extends Model
{
    use HasFactory;

    public function adjust_category()
    {
        return $this->belongsTo(AccountCategory::class, 'adjust_category_id', 'id');
    }
    public function adjust_head()
    {
        return $this->belongsTo(AccountHead::class, 'adjust_head_id', 'id');
    }
    public function fund()
    {
        return $this->belongsTo(Fund::class, 'fund_id', 'id');
    }
    public function income()
    {
        return $this->belongsTo(Income::class, 'income_id', 'id');
    }

}
