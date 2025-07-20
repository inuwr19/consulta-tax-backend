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
        Schema::create('consultants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('specialty')->nullable();
            $table->integer('experience_years')->nullable();
            $table->decimal('rating', 2, 1)->nullable(); // contoh: 4.9
            $table->integer('price_individual_service')->nullable();
            $table->integer('price_individual_jasa')->nullable();
            $table->integer('price_company_service')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultants');
    }
};
