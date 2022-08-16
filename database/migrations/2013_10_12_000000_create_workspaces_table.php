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
        Schema::create('workspaces', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('appstore_private_key', 1024)->nullable();
            $table->string('appstore_issuer_id')->nullable();
            $table->string('appstore_kid')->nullable();
            $table->string('github_org_name')->nullable()->unique();
            $table->string('github_access_token')->nullable();
            $table->string('github_template')->nullable();
            $table->string('github_topic')->nullable();
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
        Schema::dropIfExists('workspaces');
    }
};
