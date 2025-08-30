<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvanceChequesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance_cheques', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('bank_id')->nullable();
            $table->bigInteger('account_id')->nullable();
            $table->string('account_holder')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('cheque_issue_date')->nullable();
            $table->longText('benificiary')->nullable();
            $table->string('remarks')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->bigInteger('status')->default(1);
            $table->bigInteger('expense_id')->nullable();
            $table->string('attachment')->nullable();
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
        Schema::dropIfExists('advance_cheques');
    }
}
