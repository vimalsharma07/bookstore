<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'book_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->string('reviewer_name', 120)->nullable()->after('user_id');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->unique(['user_id', 'book_id']);
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'book_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('reviewer_name');
        });

        \Illuminate\Support\Facades\DB::table('reviews')->whereNull('user_id')->delete();

        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->unique(['user_id', 'book_id']);
        });
    }
};
