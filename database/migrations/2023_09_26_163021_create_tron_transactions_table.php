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
        Schema::create('tron_transactions', function (Blueprint $table) {
            // string(64) "c9503be6fde2caaf9aebfe3da3ffa1e4eb084621d76d26ad83d33cc6dae5d220"
            $table->string('id')->primary();

            // int(2)
            $table->tinyInteger('status')->default(2);
            // int(1695304839000)
            $table->bigInteger('block_ts')->default(0);
            // string(34) "TBKhDipMM7phJFPefxcPtMPM6bnJ8soWQx"
            $table->string('from_address');
            // array(0) {}
            $table->text('from_address_tag')->nullable();
            // string(34) "TY8jNABVoReroGTuPhyb9RYhfNtyB3bb1p"
            $table->string('to_address');
            // array(0) {}
            $table->text('to_address_tag')->nullable();
            // int(54891353)
            $table->bigInteger('block')->default(0);
            
            // string(5) "80001"
            $table->string('quant');
            // bool(true)
            $table->boolean('confirmed')->default(false);
            // string(7) "SUCCESS"
            $table->string('contractRet');
            // string(7) "SUCCESS"
            $table->string('finalResult');
            // bool(false)
            $table->boolean('revert')->default(false);
            
            // string(5) "trc20"
            $table->string('contract_type');
            // string(34) "TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t"
            $table->string('contract_address');

            // bool(false)
            $table->boolean('fromAddressIsContract')->default(false);
            // bool(false)
            $table->boolean('toAddressIsContract')->default(false);

            // bool(true)
            $table->boolean('riskTransaction')->default(false);

            $table->timestamps();
            $table->softDeletes();
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
