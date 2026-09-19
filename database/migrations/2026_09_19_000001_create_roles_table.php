<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();           // superadmin, admin, finance, sponsor, customer
            $table->string('display_name', 100);            // Super Administrator
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('level')->default(10); // 100=superadmin, 50=admin, 40=finance, 30=sponsor, 10=customer
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
