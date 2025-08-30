<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fund_id');
            $table->tinyInteger('type')->default(1)->comment('1:in,2:out');
            $table->string('transection_type')->nullable();
            $table->string('transection_id')->nullable();
            $table->date('transection_date')->nullable();
            $table->double('amount');
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
        Schema::dropIfExists('fund_logs');
    }
}
