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
        Schema::table('wabi_cart', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('wabi_game_profile', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('wabi_orders', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('wabi_produk', function (Blueprint $table) {
            $table->foreign('kategori_id')
                  ->references('id')
                  ->on('wabi_kategori')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wabi_cart', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('wabi_game_profile', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('wabi_orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('wabi_produk', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
        });
    }
};
