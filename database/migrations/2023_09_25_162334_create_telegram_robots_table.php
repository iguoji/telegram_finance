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
        Schema::create('telegram_robots', function (Blueprint $table) {
            $table->bigInteger('id')->primary();

            $table->string('token')->nullable()->unique();

            $table->text('commands')->nullable();
            $table->text('webhook')->nullable();

            $table->text('private')->nullable()->comment('私聊回调');
            $table->text('private_keyboard')->nullable()->comment('私聊键盘');

            $table->text('group_default')->nullable()->comment('群聊普通人回调');
            $table->text('group_operator')->nullable()->comment('群聊操作员回调');
            $table->text('group_administrator')->nullable()->comment('群聊管理员回调');

            $table->integer('trial_duration')->default(0)->comment('可试用时长');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_robots');
    }
};
