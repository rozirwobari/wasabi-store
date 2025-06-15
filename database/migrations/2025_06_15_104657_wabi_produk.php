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
        Schema::create('wabi_produk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->mediumText('label')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->integer('harga')->nullable();
            $table->json('images')->default('[]');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wabi_produk');
    }
};
