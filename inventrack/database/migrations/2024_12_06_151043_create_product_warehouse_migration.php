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
        Schema::create('product_warehouse', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('min_stock');
            $table->unsignedSmallInteger('product_id');
            $table->foreign('product_id', 'fk_products_warehouse')
                ->references('id')->on('products')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedTinyInteger('warehouse_id');
            $table->foreign('warehouse_id', 'fk_product_warehouses')
                ->references('id')->on('warehouses')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_warehouse_migration');
    }
};
