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
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('badge_type');
            $table->string('nom');
            $table->string('description');
            $table->string('icone'); // Classe font-awesome ou emoji
            $table->timestamp('obtenu_le')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'badge_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_badges');
    }
};
