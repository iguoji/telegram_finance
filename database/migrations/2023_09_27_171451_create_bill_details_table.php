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
        Schema::create('bill_details', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('bill_id');
            $table->bigInteger('group_id')->index();
            
            $table->tinyInteger('type')->default(1)->index()->comment('类型，1入，2出');

            $table->bigInteger('user_id')->nullable();
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            $table->decimal('money', 14, 4);

            $table->bigInteger('reply_user_id')->nullable();
            $table->string('reply_username')->nullable();
            $table->string('reply_first_name')->nullable();
            $table->string('reply_last_name')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_details');
    }
};
