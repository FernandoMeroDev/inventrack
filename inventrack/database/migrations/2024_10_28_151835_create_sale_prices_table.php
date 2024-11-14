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
        Schema::create('sale_prices', function (Blueprint $table) {
            $table->mediumIncrements('id')->unsigned();
            $table->primary('id');
            $table->unsignedTinyInteger('units_number');
            $table->decimal('value',10,6);
            $table->unsignedSmallInteger('product_id');
            $table->foreign('product_id', 'fk_product_sale_prices')
                ->references('id')->on('products')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_prices');
    }
};
