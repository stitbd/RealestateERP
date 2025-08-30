<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
Use DB;

class Leave extends Model
{
    use HasFactory;
    
    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'id' );
    }

    
    public function leave_type(){
        return $this->belongsTo(LeaveType::class, 'leave_type_id', 'id' );
    }

}
