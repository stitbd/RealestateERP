<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankGarantiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_garanties', function (Blueprint $table) {
            $table->id();
            $table->date('garanty_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('description')->nullable();
            $table->string('reference')->nullable();
            $table->double('amount')->nullable();
            $table->double('bank_credit_limit')->nullable();
            $table->string('purpose')->nullable();
            $table->date('valid_date')->nullable();
            $table->string('remarks')->nullable();
            $table->string('attachment')->nullable();
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
        Schema::dropIfExists('bank_garanties');
    }
}
