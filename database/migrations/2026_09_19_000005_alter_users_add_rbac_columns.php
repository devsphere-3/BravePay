<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom role_id setelah email_verified_at
            $table->unsignedBigInteger('role_id')->nullable()->after('email_verified_at');
            $table->string('phone', 20)->nullable()->after('name');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('role_id');
            $table->timestamp('last_login_at')->nullable()->after('status');

            $table->foreign('role_id')
                  ->references('id')->on('roles')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'phone', 'status', 'last_login_at']);
        });
    }
};
