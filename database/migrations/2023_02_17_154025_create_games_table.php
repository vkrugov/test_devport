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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique();
            $table->unsignedBigInteger('user_id');
            $table->dateTime('expired_at')->index();
            $table->timestamps();

            $table->foreign('user_id')
                  ->on('users')
                  ->references('id')
                  ->onDelete('cascade');
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
