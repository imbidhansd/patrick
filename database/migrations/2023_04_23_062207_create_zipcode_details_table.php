<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZipcodeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zipcode_details', function (Blueprint $table) {
            $table->id();
            $table->string('parent_zip_code', 255);
            $table->string('zip_code', 255);
            $table->double('distance', 8, 2);
            $table->string('city', 255);
            $table->string('state', 255);
            $table->bigInteger('state_id');
            $table->enum('status', ['active', 'inactive']);
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
        Schema::dropIfExists('zipcode_details');
    }
}
