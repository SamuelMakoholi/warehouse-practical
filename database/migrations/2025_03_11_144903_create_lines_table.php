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
        Schema::create('lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rack_id');
            $table->string('name');
            $table->enum('type', ['carton', 'loose', 'mixed'])->default('carton');
            $table->integer('max_allowed_capacity')->comment('Maximum capacity in kg or number of items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lines');
    }
};
