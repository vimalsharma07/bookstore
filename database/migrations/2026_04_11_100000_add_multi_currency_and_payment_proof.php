<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->unsignedInteger('price_cents_usd')->nullable()->after('currency');
            $table->unsignedInteger('price_cents_eur')->nullable()->after('price_cents_usd');
            $table->unsignedInteger('price_cents_inr')->nullable()->after('price_cents_eur');
        });

        DB::table('books')->update([
            'price_cents_usd' => DB::raw('price_cents'),
            'price_cents_eur' => DB::raw('price_cents'),
            'price_cents_inr' => DB::raw('price_cents'),
        ]);

        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_proof_path')->nullable()->after('billing_details');
            $table->timestamp('payment_proof_submitted_at')->nullable()->after('payment_proof_path');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_proof_path', 'payment_proof_submitted_at']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['price_cents_usd', 'price_cents_eur', 'price_cents_inr']);
        });
    }
};
