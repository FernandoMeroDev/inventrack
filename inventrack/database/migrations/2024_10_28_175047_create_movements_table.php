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
        Schema::create('movements', function (Blueprint $table) {
            $table->mediumIncrements('id')->unsigned();
            $table->primary('id');
            $table->smallInteger('amount')->unsigned();
            $table->bigInteger('existences');
            $table->decimal('price', 10, 6)->unsigned()->nullable()->default(null);
            $table->smallInteger('product_id')->unsigned();
            $table->foreign('product_id', 'fk_movements_product')
                ->references('id')->on('products')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->mediumInteger('receipt_id')->unsigned();
            $table->foreign('receipt_id', 'fk_movements_receipt')
                ->references('id')->on('receipts')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
