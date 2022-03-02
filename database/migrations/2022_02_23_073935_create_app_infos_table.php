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
            $table->string('app_icon')->default(null)->nullable();
            $table->string('app_name')->unique();
            $table->string('app_bundle')->unique();
            $table->string('appstore_id')->unique();
            $table->string('fb_app_id')->default(null)->nullable();
            $table->string('elephant_id')->default(null)->nullable();
            $table->string('elephant_secret')->default(null)->nullable();
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
