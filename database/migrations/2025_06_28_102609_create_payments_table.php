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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->string('method');
            $table->string('reference_number')->unique();
            $table->text('proof_url')->nullable();
            $table->enum('status', ['waiting', 'confirmed', 'rejected'])->default('waiting');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'appointment_id', 'status']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
