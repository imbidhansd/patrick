<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBannerAltToSiteLogosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_logos', function (Blueprint $table) {
            $table->string('banner_alt', 250)->nullable()->after('banner_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_logos', function (Blueprint $table) {
            $table->dropColumn('banner_alt');
        });
    }
}
