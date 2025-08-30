<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->string('salary_id');
            $table->bigInteger('employee_id');
            $table->bigInteger('company_id');
            $table->string('month');
            $table->date('start_date');
            $table->date('end_date');
            $table->double('gross_salary');
            $table->double('addition')->nullable()->default(0);
            $table->double('deduction')->nullable()->default(0);
            $table->double('total_salary');
            $table->double('remarks');
            $table->string('attachment');
            $table->tinyInteger('status')->default('1');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('employee_salaries');
    }
}
