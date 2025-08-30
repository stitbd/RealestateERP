<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class EmployeeAttendance extends Model
{
    use HasFactory;
    
    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'id' );
    }

    public static function leave_status($employee_id,$date) {
        $sql = DB::table('leaves')->where('status','=',1)->where('employee_id','=',$employee_id)->whereDate('leave_date','=',$date)->first();
        return $sql;
    }

    public static function attendance_status($employee_id,$date) {
        $sql = DB::table('employee_attendances')->where('status','=',1)->where('employee_id','=',$employee_id)->whereDate('attendance_date','=',$date)->first();
        return $sql;
    }

    public static function holiday_status($date) {
        $sql = DB::table('holidays')->where('status','=',1)->whereDate('holy_date','=',$date)->first();
        return $sql;
    }
}
