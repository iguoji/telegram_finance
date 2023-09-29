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
        Schema::dropIfExists('tron_transactions');

        Schema::create('tron_transactions', function(Blueprint $table){
            $table->string('id')->primary();

            $table->string('from');
            $table->string('to');
            $table->decimal('number', 16, 6);
            $table->bigInteger('timestamp');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tron_transactions');
    }
};
