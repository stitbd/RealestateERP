<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('card_id');
            $table->string('machine_id');
            $table->string('name');
            $table->bigInteger('company_id');
            $table->bigInteger('department_id');
            $table->bigInteger('section_id')->nullable();
            $table->bigInteger('designation_id');
            $table->bigInteger('branch_id')->nullable();
            $table->bigInteger('grade_id');
            $table->bigInteger('schedule_id');
            $table->bigInteger('payment_type_id')->nullable();
            $table->date('joining_date');
            $table->string('job_location')->nullable();
            $table->string('gross_salary')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('nid')->nullable();
            $table->string('etin')->nullable();
            $table->date('birthdate')->nullable();

            
            $table->string('mobile_no')->nullable();
            $table->string('present_address')->nullable();
            $table->string('parmanent_address')->nullable();
            $table->string('employee_image')->nullable();
            $table->string('employee_signature')->nullable();
            $table->string('academic_degree')->nullable();
            $table->string('acdemic_institute')->nullable();
            $table->string('passing_year')->nullable();
            $table->string('working_experience')->nullable();

            $table->string('religion')->nullable();
            $table->string('bank_ac_no')->nullable();
            $table->string('nominee_name')->nullable();
            $table->string('nominee_relation')->nullable();
            $table->string('nominee_father_name')->nullable();
            $table->string('nominee_mother_name')->nullable();
            $table->string('nominee_spouse_name')->nullable();
            $table->string('nominee_nid')->nullable();
            $table->string('nominee_cell_no')->nullable();
            $table->string('nominee_address')->nullable();
            $table->string('nominee_image')->nullable();
            
            $table->tinyInteger('status')->default('1');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('modified_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
