<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDomainSlugToSiteLogosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_logos', function (Blueprint $table) {
            $table->string('domain_slug', 25)->after('banner_for')->default('tp');
        });

        // Set default value for existing rows
        \DB::table('site_logos')->update(['domain_slug' => 'tp']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_logos', function (Blueprint $table) {
            $table->dropColumn('domain_slug');
        });
    }
}
