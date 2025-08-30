<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expense extends Model
{
    use HasFactory;

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }
    public function project(){
        return $this->belongsTo(Project::class, 'project_id', 'id' );
    }
    public function employee(){
        return $this->belongsTo(Employee::class, 'expense_by','id');
    }
   
    public function fund(){
        return $this->belongsTo(Fund::class, 'fund_id', 'id' );
    }
    
    public function expense_details(){
        return $this->hasMany(ExpenseDetails::class);
    }
    public function category(){
        return $this->belongsTo(AccountCategory::class, 'category_id', 'id' );
    }
    public function head(){
        return $this->belongsTo(AccountHead::class, 'head_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'created_by','id');
    }

    public function update_by(){
        return $this->belongsTo(User::class, 'updated_by','id');
    }
    public function reject_by(){
        return $this->belongsTo(User::class, 'rejected_by','id');
    }

    public function approved_by(){
        return $this->belongsTo(User::class, 'approved_by','id');
    }
    
    
}
