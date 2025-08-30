<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_project', function (Blueprint $table) {
            $table->id();
            $table->string('project_id');
            $table->string('company_id');
            $table->string('name');
            $table->string('location');
            $table->string('description')->nullable();
            $table->string('authority');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->float('project_amount')->nullable();
            $table->float('estimated_cost')->nullable();
            $table->float('estimated_profit')->nullable();
            $table->tinyInteger('status')->default('1')->comment('1:not started, 2:in progress, 3:on hold, 4:canceled, 5:completed');
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
        Schema::dropIfExists('sub_project');
    }
}
