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
        Schema::create('receipt_types', function (Blueprint $table) {
            $table->tinyIncrements('id')->unsigned();
            $table->primary('id');
            $table->string('name', 45)->unique();
            $table->string('label', 45)->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_types');
    }
};
