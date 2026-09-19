<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->boolean('granted')->default(true);  // true=tambah, false=cabut
            $table->string('reason', 255)->nullable();  // alasan override
            $table->unsignedBigInteger('granted_by')->nullable(); // superadmin yang memberikan
            $table->timestamp('expires_at')->nullable(); // opsional: batas waktu
            $table->timestamp('created_at')->nullable();

            $table->unique(['user_id', 'permission_id']);

            $table->foreign('granted_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
