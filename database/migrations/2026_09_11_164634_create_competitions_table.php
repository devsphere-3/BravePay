<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('event_name')->nullable();
            $table->text('description')->nullable();
            $table->string('poster')->nullable();
            $table->string('category');
            $table->unsignedBigInteger('price');             // harga normal (satuan rupiah)
            $table->unsignedBigInteger('price_community')->nullable();  // harga komunitas
            $table->unsignedBigInteger('price_early_bird')->nullable(); // harga early bird
            $table->unsignedInteger('quota')->default(0);    // 0 = tidak terbatas
            $table->date('event_date')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->default('open');       // open|closed|coming_soon|full
            $table->string('unit')->default('peserta');      // peserta | team
            $table->text('rules')->nullable();
            $table->text('requirements')->nullable();
            $table->text('schedule')->nullable();            // JSON string
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
