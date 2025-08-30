<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeSalaryPaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_salary_payment_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('payment_id');
            $table->string('receiver_name');
            $table->string('mobile_no');
            $table->string('nid');
            $table->string('address');
            $table->string('check_number');
            $table->string('check_issue_date');
            $table->string('bank_name');
            $table->string('bank_account_no');
            $table->string('account_holder_name');
            $table->string('payment_note');
            $table->string('remarks');
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
        Schema::dropIfExists('employee_salary_payment_details');
    }
}
