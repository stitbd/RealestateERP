<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }

    public function other_company(){
        return $this->belongsTo(Company::class, 'other_company_id', 'id' );
    }
    public function project(){
        return $this->belongsTo(Project::class, 'project_id', 'id' );
    }
    public function employee(){
        return $this->belongsTo(Employee::class, 'created_by','id');
    }
    public function receiver(){
        return $this->belongsTo(User::class, 'created_by','id');
    }

    public function fund(){
        return $this->belongsTo(Fund::class, 'fund_id', 'id' );
    }

    public function bank(){
        return $this->belongsTo(Bank::class, 'bank_id', 'id' );
    }
    public function account(){
        return $this->belongsTo(BankAccount::class, 'account_id', 'id');
    }

    public function category(){
        return $this->belongsTo(AccountCategory::class, 'category_id', 'id' );
    }
    public function head(){
        return $this->belongsTo(AccountHead::class, 'head_id', 'id' );
    }
    
    public function income_details(){
        return $this->hasMany(IncomeDetails::class);
    }
}
