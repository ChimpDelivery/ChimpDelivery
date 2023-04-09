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
        Schema::create('github_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')
                ->references('id')
                ->on('workspaces')
                ->onDelete('cascade');
            $table->text('personal_access_token')->nullable();
            $table->string('organization_name')->nullable()->unique();
            $table->string('template_name')->nullable();
            $table->string('topic_name')->nullable();
            $table->boolean('public_repo')->default(true);
            $table->boolean('private_repo')->default(false);
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
        Schema::dropIfExists('github_settings');
    }
};
