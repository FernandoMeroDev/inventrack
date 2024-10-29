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
        Schema::create('shelves', function (Blueprint $table) {
            $table->smallIncrements('id')->unsigned();
            $table->primary('id');
            $table->string('name', 45);
            $table->unsignedTinyInteger('warehouse_id');
            $table->foreign('warehouse_id', 'fk_shelves_warehouse')
                ->references('id')->on('warehouses')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shelves');
    }
};
