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
        Schema::table('prices', function(Blueprint $table){
            $table->integer('year')->nullable()->default(0)->after('callback');
            $table->integer('month')->nullable()->default(0)->after('year');
            $table->integer('day')->nullable()->default(0)->after('month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('prices', ['year', 'month', 'day']);
    }
};
