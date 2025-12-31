<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTableName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('affiliate_top_level_categories', 'affiliate_main_categories');
        Schema::table('affiliate_main_categories', function (Blueprint $table) {
            $table->renameColumn('top_level_category_id', 'main_category_id');
            $table->integer('service_category_type_id')->nullable();
        });
        Artisan::call('make:model', ['name' => 'AffiliateMainCategories']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('affiliate_main_categories', 'affiliate_top_level_categories');
        Schema::table('affiliate_top_level_categories', function (Blueprint $table) {
            $table->renameColumn('main_category_id', 'top_level_category_id');
            $table->dropColumn('service_category_type_id');
        });
    }
}
