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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author');
            $table->longText('description');
            $table->unsignedInteger('price_cents');
            $table->string('currency', 3)->default('USD');

            $table->string('cover_path')->nullable();
            $table->string('pdf_path');
            $table->string('preview_pdf_path')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();

            $table->unsignedInteger('purchases_count')->default(0);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->decimal('rating_avg', 3, 2)->default(0);

            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
