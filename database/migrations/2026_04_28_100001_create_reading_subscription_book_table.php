<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_subscription_book', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reading_subscription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['reading_subscription_id', 'book_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_subscription_book');
    }
};
