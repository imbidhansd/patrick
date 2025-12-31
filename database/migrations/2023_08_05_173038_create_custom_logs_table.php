<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_logs', function (Blueprint $table) {
            $table->id();
            $table->string('channel');
            $table->string('level');
            $table->text('message');
            $table->text('context')->nullable();
            $table->string('key_identifier')->nullable();
            $table->string('key_identifier_type')->nullable();
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
        Schema::dropIfExists('custom_logs');
    }
}
