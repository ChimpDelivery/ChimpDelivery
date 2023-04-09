<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('google_play_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')
                ->references('id')
                ->on('workspaces')
                ->onDelete('cascade');
            $table->text('keystore_path')->nullable();
            $table->text('keystore_pass')->nullable();
            $table->text('service_account')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('google_play_settings');
    }
};
