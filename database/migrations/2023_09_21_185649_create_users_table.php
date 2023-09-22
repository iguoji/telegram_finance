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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('uid')->unique()->comment('Telegram User ID');
            $table->boolean('is_bot')->default(false)->comment('是否为机器人');
            $table->string('first_name')->comment('名称');
            $table->string('last_name')->nullable()->comment('姓氏');
            $table->string('username')->nullable()->comment('用户名');
            $table->string('language_code')->nullable()->comment('语言');
            $table->boolean('is_premium')->nullable()->default(false)->comment('是否为高级用户');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
