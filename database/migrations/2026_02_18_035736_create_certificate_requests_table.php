<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('resident_id')->constrained()->onDelete('cascade');
            $table->enum('certificate_type', [
                'barangay_clearance',
                'proof_of_residency',
                'certificate_of_indigency',
                'barangay_permit'
            ]);
            $table->text('purpose');
            $table->enum('status', ['pending', 'in_progress', 'ready_for_pickup', 'completed'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_requests');
    }
};