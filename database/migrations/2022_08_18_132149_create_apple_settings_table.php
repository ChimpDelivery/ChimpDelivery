<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up() : void
    {
        Schema::create('apple_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')
                ->references('id')
                ->on('workspaces')
                ->onDelete('cascade');
            $table->text('usermail')->nullable();
            $table->text('app_specific_pass')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down() : void
    {
        Schema::dropIfExists('apple_settings');
    }
};
