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
        Schema::create('receipts', function (Blueprint $table) {
            $table->mediumIncrements('id')->unsigned();
            $table->primary('id');
            $table->date('issuance_date')->nullable()->default(null);
            $table->string('comment', 500)->nullable()->default(null);
            $table->tinyInteger('consolidated')->unsigned()->nullable()->default(null);
            $table->timestamps();
            $table->tinyInteger('type_id')->unsigned();
            $table->foreign('type_id', 'fk_receipts_type')
                ->references('id')->on('receipt_types')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->tinyInteger('warehouse_id')->unsigned();
            $table->foreign('warehouse_id', 'fk_receipts_warehouse')
                ->references('id')->on('warehouses')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
