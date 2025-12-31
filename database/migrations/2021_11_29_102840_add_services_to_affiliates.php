<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServicesToAffiliates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->bigInteger('trade_id')->nullable();
            $table->bigInteger('service_category_type_id')->nullable();
            $table->text('top_level_categories')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->dropColumn('trade_id');
            $table->dropColumn('service_category_type_id');
            $table->dropColumn('top_level_categories');
        });
    }
}
