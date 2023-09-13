<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scan_info', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_name');
            $table->json('list_fb_ids');
            $table->integer('pass_day');
            $table->tinyInteger('status')->default(0)->comment("0:pending, 1: process, 2: done");
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
        Schema::dropIfExists('scan_info');
    }
};
