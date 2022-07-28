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
        Schema::table('app_infos', function (Blueprint $table) {
            $table->renameColumn('elephant_id', 'ga_id');
            $table->renameColumn('elephant_secret', 'ga_secret');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_infos', function (Blueprint $table) {
            $table->renameColumn('ga_id', 'elephant_id');
            $table->renameColumn('ga_secret', 'elephant_secret');
        });
    }
};
