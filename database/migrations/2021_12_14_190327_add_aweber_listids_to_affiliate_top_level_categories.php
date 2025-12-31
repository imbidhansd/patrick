<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAweberListidsToAffiliateTopLevelCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('affiliate_top_level_categories', function (Blueprint $table) {
            $table->string('aweber_member_listid', 255)->nullable();
            $table->string('aweber_non_member_listid', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('affiliate_top_level_categories', function (Blueprint $table) {
            $table->dropColumn('aweber_member_listid');
            $table->dropColumn('aweber_non_member_listid');
        });
    }
}
