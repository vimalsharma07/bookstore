<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('source')->default('razorpay');
            $table->string('event')->nullable();
            $table->string('signature')->nullable();
            $table->json('request_headers')->nullable();
            $table->longText('request_payload')->nullable();
            $table->string('forwarded_to')->nullable();
            $table->unsignedSmallInteger('forward_status_code')->nullable();
            $table->longText('forward_response_body')->nullable();
            $table->boolean('is_forward_success')->default(false);
            $table->timestamp('forwarded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
