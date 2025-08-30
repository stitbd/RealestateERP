<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkStatusLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_status_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('work_status_id');
            $table->dateTime('log_date');
            $table->integer('man_power');
            $table->integer('description');
            $table->float('previous_work');
            $table->integer('today_work');
            $table->integer('total_work');
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
        Schema::dropIfExists('work_status_logs');
    }
}
