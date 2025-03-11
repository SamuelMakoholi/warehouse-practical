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
        // First drop existing tables in reverse order to avoid foreign key constraints
        Schema::dropIfExists('packages');
        Schema::dropIfExists('pallets');
        Schema::dropIfExists('racks');
        Schema::dropIfExists('lines');
        Schema::dropIfExists('warehouses');
        
        // Create warehouses table
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('max_capacity', 10, 2)->comment('Maximum capacity in kg');
            $table->timestamps();
        });

        // Create lines table with warehouse relationship
        Schema::create('lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->string('name');
            $table->enum('type', ['carton', 'loose', 'mixed'])->default('carton');
            $table->decimal('max_allowed_capacity', 10, 2)->comment('Maximum capacity in kg');
            $table->timestamps();
            
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
        });

        // Create racks table with line relationship
        Schema::create('racks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('line_id');
            $table->string('serial_number')->unique();
            $table->decimal('capacity', 10, 2)->comment('Capacity in kg');
            $table->timestamps();
            
            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
        });

        // Create pallets table with rack relationship
        Schema::create('pallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rack_id');
            $table->string('serial_number')->unique();
            $table->decimal('max_weight', 10, 2)->comment('Maximum weight in kg');
            $table->timestamps();
            
            $table->foreign('rack_id')->references('id')->on('racks')->onDelete('cascade');
        });

        // Create quality_marks table
        Schema::create('quality_marks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create packages table with pallet and quality mark relationships
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->enum('type', ['loose', 'carton'])->default('carton');
            $table->decimal('mass', 10, 2)->comment('Mass in kg');
            $table->string('barcode');
            $table->unsignedBigInteger('pallet_id')->nullable();
            $table->unsignedBigInteger('quality_mark_id');
            $table->boolean('is_discarded')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('pallet_id')->references('id')->on('pallets')->onDelete('set null');
            $table->foreign('quality_mark_id')->references('id')->on('quality_marks')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
        Schema::dropIfExists('quality_marks');
        Schema::dropIfExists('pallets');
        Schema::dropIfExists('racks');
        Schema::dropIfExists('lines');
        Schema::dropIfExists('warehouses');
    }
};
