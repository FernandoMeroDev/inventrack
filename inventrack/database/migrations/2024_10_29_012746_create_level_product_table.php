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
        Schema::create('level_product', function (Blueprint $table) {
            $table->mediumIncrements('id')->unsigned();
            $table->primary('id');
            $table->tinyInteger('amount')->unsigned();
            $table->unsignedSmallInteger('level_id');
            $table->foreign('level_id', 'fk_levels_product')
                ->references('id')->on('levels')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedSmallInteger('product_id');
            $table->foreign('product_id', 'fk_level_products')
                ->references('id')->on('products')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_product');
    }
};
