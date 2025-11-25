<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * php artisan migrate --path=database/migrations/2025_11_22_123035_create_investors_table.php
     */
    public function up(): void
    {
        Schema::create('investors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('mobile_number')->nullable();
            $table->text('address')->nullable();
            $table->string('occupation')->nullable();
            $table->decimal('investment_amount', 12, 2)->nullable();
            $table->string('status')->default(1);
            $table->text('remark')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investors');
    }
};
