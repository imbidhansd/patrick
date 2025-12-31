<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCompanyFromAffiliateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->string('affiliate_name');
            $table->dropForeign('affiliates_company_id_foreign');
            $table->dropColumn(['company_id',
            'company_name', 
            'company_phone', 
            'internal_company_email', 
            'internal_company_name']);
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
            $table->dropColumn('affiliate_name');
            $table->integer('company_id');   
            $table->foreign('company_id')->nullable()->references('id')->on('companies');         
            $table->string('company_name')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('internal_company_email')->nullable();
            $table->string('internal_company_name')->nullable();
        });
    }
}
