<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->string('phone');
            $table->unsignedInteger('participant_count')->default(1);
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->string('status')->default('pending'); // pending | paid | failed | expired
            $table->json('participants')->nullable();      // [{name, date_of_birth}]
            $table->json('tickets')->nullable();           // [{ticket_code, participant, status}]
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
