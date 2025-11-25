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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable();
            $table->string("slug")->nullable();
            $table->string("image")->nullable();
            $table->text("description")->nullable();
            $table->boolean("status")->default(0);
            $table->bigInteger("views")->default(0);
            $table->foreignId('category_id')
                ->constrained('blog_categories')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     *
 php artisan migrate --path=database/migrations/2025_11_20_102557_create_blogs_table.php
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
