<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function department(){
        return $this->belongsTo(Department::class, 'department_id', 'id' );
    }
    public function section(){
        return $this->belongsTo(Section::class, 'section_id', 'id' );
    }
    public function designation(){
        return $this->belongsTo(Designation::class, 'designation_id', 'id' );
    }
    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }
    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id', 'id' );
    }
    public function grade(){
        return $this->belongsTo(Grade::class, 'grade_id', 'id' );
    }
    public function schedule(){
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id' );
    }
    public function payment_type(){
        return $this->belongsTo(PaymentType::class, 'payment_type_id', 'id' );
    }

    public function created_usesr() {
        return $this->belongsTo(User::class, 'created_by', 'id' );
    }

    public function modiifed_user() {
        return $this->belongsTo(User::class, 'modiifed_by', 'id' );
    }
}
