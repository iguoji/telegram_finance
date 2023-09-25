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
        Schema::create('telegram_users', function (Blueprint $table) {
            $table->bigInteger('id')->primary();

            $table->tinyInteger('status')->default(1);
            $table->boolean('is_bot')->default(false);

            $table->string('first_name');
            $table->string('last_name')->nullable();

            $table->string('username')->nullable()->unique();
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();

            $table->timestamp('vip_at')->nullable();
            $table->timestamp('trial_at')->nullable();

            $table->string('language_code')->nullable();
            $table->boolean('is_premium')->default(false)->nullable();
            $table->boolean('added_to_attachment_menu')->default(false)->nullable();
            $table->boolean('can_join_groups')->default(false)->nullable();
            $table->boolean('can_read_all_group_messages')->default(false)->nullable();
            $table->boolean('supports_inline_queries')->default(false)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_users');
    }
};
