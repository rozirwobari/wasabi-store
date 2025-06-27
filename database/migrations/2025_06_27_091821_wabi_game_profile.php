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
        Schema::create('wabi_game_profile', function (Blueprint $table) {
            $table->id(); // auto increment primary key
            $table->integer('user_id')->default(0);
            $table->string('identifier', 150)->nullable();
            $table->mediumText('name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wabi_game_profile');
    }
};
