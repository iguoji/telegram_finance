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
        Schema::create('robots', function (Blueprint $table) {
            $table->id();

            $table->tinyInteger('status')->default(1)->comment('状态');
            $table->string('token')->unique()->comment('Token');

            $table->string('first_name')->comment('名称');
            $table->string('last_name')->nullable()->comment('姓氏');
            $table->string('username')->nullable()->comment('用户名');

            $table->boolean('can_join_groups')->nullable()->default(true)->comment('can_join_groups');
            $table->boolean('can_read_all_group_messages')->nullable()->default(true)->comment('can_read_all_group_messages');
            $table->boolean('supports_inline_queries')->nullable()->default(false)->comment('supports_inline_queries');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('robots');
    }
};
