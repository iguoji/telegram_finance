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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->tinyInteger('status')->default(2)->comment('状态0失败1成功2待支付');

            $table->bigInteger('user_id');
            $table->bigInteger('robot_id');
            $table->integer('price_id');

            $table->decimal('money', 12, 4)->default(0);
            $table->string('hash')->nullable()->comment('交易Hash');
            $table->timestamp('payment_at')->nullable()->comment('查到的时间');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
