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
            $table->id(); // bigint unsigned auto_increment primary key
            $table->string('no_invoice', 150)->default('0');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('identifier', 150)->nullable();
            $table->json('items')->default('[]');
            $table->integer('total');
            $table->integer('status')->default(0);
            $table->string('snap_token', 200)->nullable();
            $table->longText('data_midtrans')->nullable();
            $table->longText('tgl_transaksi')->nullable();
            $table->longText('reason_game')->default('[]');
            $table->timestamps();

            // Index
            $table->index('user_id');
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
