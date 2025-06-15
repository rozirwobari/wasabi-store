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
        // Foreign key untuk wabi_produk -> wabi_kategori
        Schema::table('wabi_produk', function (Blueprint $table) {
            $table->foreign('kategori_id')->references('id')->on('wabi_kategori')->onDelete('set null');
        });

        // Foreign key untuk wabi_cart -> wabi_produk
        Schema::table('wabi_cart', function (Blueprint $table) {
            $table->foreign('produk_id')->references('id')->on('wabi_produk')->onDelete('cascade');
        });

        // Foreign key untuk wabi_cart -> users
        Schema::table('wabi_cart', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        // Foreign key untuk wabi_orders -> users
        Schema::table('wabi_orders', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wabi_produk', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
        });

        Schema::table('wabi_cart', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('wabi_orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
};
