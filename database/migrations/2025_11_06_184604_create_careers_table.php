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
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('position');
            $table->string('slug');
            $table->string('department')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->date('opening_date')->nullable();
            $table->date('deadline')->nullable();
            $table->integer('vacancies')->default(1)->nullable();
            $table->string('type')->default('Full-time');
            $table->string('salary')->nullable()->default('Negotiable');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};
