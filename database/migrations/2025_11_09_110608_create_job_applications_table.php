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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            // Relation to career
            $table->foreignId('career_id')->constrained()->onDelete('cascade');

            // Basic applicant info
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('district')->nullable();

            // Optional extra info
            $table->string('expected_salary')->nullable();
            $table->string('current_position')->nullable();
            $table->string('experience_level')->nullable();

            // File uploads
            $table->string('resume')->nullable();
            $table->string('portfolio_link')->nullable();

            // Message or cover letter
            $table->text('message')->nullable();

            // Application status
            $table->enum('status', ['pending', 'shortlisted', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
