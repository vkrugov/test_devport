<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            $table->smallInteger('result');
            $table->integer('win');
            $table->timestamps();

            $table->foreign('game_id')
                  ->on('games')
                  ->references('id')
                  ->onDelete('cascade');

            $table->index('created_at');
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
