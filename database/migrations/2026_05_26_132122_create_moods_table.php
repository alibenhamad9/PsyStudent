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
        Schema::create('moods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->tinyInteger('humeur'); // 1 = 😔, 2 = 😐, 3 = 🙂, 4 = 😄
            $table->string('note')->nullable();
            $table->date('date_suivi');
            $table->timestamps();

            // Un seul enregistrement d'humeur par utilisateur et par jour
            $table->unique(['user_id', 'date_suivi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moods');
    }
};
