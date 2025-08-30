<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('code_no')->nullable();
            $table->string('voucher_no')->nullable();
            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('project_id')->nullable();
            $table->bigInteger('fund_id')->nullable();
            $table->string('bank_id')->nullable();
            $table->bigInteger('receive_by')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('payment_type')->nullable();
            $table->double('amount')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('cheque_issue_date')->nullable();
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
        Schema::dropIfExists('incomes');
    }
}
