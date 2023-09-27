<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('telegram_groups', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('old_id')->nullable()->unique();
            $table->bigInteger('new_id')->nullable()->unique();

            $table->string('type');
            $table->string('title');
            $table->string('status');
            
            $table->bigInteger('inviter');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_groups');
    }
};
