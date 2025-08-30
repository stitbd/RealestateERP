<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            "CREATE OR REPLACE VIEW search_views AS
               SELECT particulars, 'particulars' 
               FROM expenses
               UNION ALL
               SELECT category_name, 'category_name'
               FROM account_categories
               UNION ALL
               SELECT head_name, 'head_name'
               FROM account_heads;"
          );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('search_views');
    }
}
