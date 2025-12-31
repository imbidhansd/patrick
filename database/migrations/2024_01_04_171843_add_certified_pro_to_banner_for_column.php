<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCertifiedProToBannerForColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE site_logos MODIFY COLUMN banner_for ENUM('Founding Member','Official Member','Recommended Company','Certified Pro')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE site_logos MODIFY COLUMN banner_for ENUM('Founding Member','Official Member','Recommended Company')");
    }
}
