<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_details', function (Blueprint $table) {
            $table->id();
            $table->date('details_date');
            $table->bigInteger('sales_id');
            $table->bigInteger('item_id');
            $table->bigInteger('project_id');
            $table->bigInteger('company_id');
            $table->bigInteger('details');
            $table->bigInteger('qty');
            $table->bigInteger('rate');
            $table->bigInteger('amount');
            $table->tinyInteger('status')->default(1)->nullable();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
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
        Schema::dropIfExists('sales_details');
    }
}
