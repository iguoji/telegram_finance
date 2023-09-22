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
        Schema::table('users', function(Blueprint $table){
            $table->timestamp('vip_at')->nullable()->comment('会员到期的时间')->after('is_premium');
            $table->timestamp('trial_at')->nullable()->comment('试用到期的时间')->after('vip_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('users', ['vip_at', 'trial_at']);
    }
};
