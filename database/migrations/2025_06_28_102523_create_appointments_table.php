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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('time');
            $table->enum('method', ['online', 'offline']);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'done'])->default('pending');
            $table->text('gmeet_link')->nullable(); // diisi oleh admin
            $table->text('notes')->nullable();      // kebutuhan user saat booking
            $table->foreignId('consultant_id')->nullable()->constrained()->onDelete('set null');
            $table->string('service_type')->nullable(); // individual-service, individual-jasa, etc.
            $table->enum('individual_service_type', ['spt-reporting', 'income-tax-calculation'])->nullable();
            $table->string('nama')->nullable();
            $table->string('nik', 20)->nullable();
            $table->string('npwp', 25)->nullable();
            $table->string('efin', 25)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'date', 'status']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
