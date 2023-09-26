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
        Schema::table('orders', function(Blueprint $table){
            $table->boolean('is_fill')->default(false)->comment('是否补充了时间')->after('price_id');
            $table->timestamp('user_trial_at')->nullable()->comment('下单时该账号的到期时间')->after('payment_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('orders', ['is_fill', 'user_trial_at']);
    }
};
