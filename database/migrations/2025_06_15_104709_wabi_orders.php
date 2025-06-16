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
        Schema::create('wabi_orders', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice', 150)->default('0');
            $table->unsignedBigInteger('user_id');
            $table->json('items')->default('[]');
            $table->integer('total');
            $table->tinyInteger('status')->default(0);
            $table->string('snap_token', 200)->nullable();
            $table->json('data_midtrans')->nullable();
            $table->json('tgl_transaksi')->default('[]');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wabi_orders');
    }
};
