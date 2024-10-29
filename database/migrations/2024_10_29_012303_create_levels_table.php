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
        Schema::create('levels', function (Blueprint $table) {
            $table->smallIncrements('id')->unsigned();
            $table->primary('id');
            $table->tinyInteger('number')->unsigned();
            $table->unsignedSmallInteger('shelf_id');
            $table->foreign('shelf_id', 'fk_levels_shelf')
                ->references('id')->on('shelves')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
