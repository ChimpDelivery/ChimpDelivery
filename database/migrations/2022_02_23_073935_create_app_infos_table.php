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
        Schema::create('app_infos', function (Blueprint $table) {
            $table->id();
            $table->string('app_icon')->nullable();
            $table->string('app_name');
            $table->string('app_bundle');
            $table->string('appstore_id');
            $table->string('fb_app_id')->nullable();
            $table->string('elephant_id')->nullable();
            $table->string('elephant_secret')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_infos');
    }
};
