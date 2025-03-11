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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->enum('type', ['loose', 'carton'])->default('carton');
            $table->unsignedBigInteger('pallet_id')->nullable();
            $table->unsignedBigInteger('quality_mark_id');
            $table->decimal('mass', 8, 2)->comment('Mass of the product in kg');
            $table->string('barcode');
            $table->boolean('is_discarded')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
