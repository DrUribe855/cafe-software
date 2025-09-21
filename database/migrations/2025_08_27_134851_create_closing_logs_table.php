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
        Schema::create('closing_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('establishment_id');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('refrigerator_id')->constrained('refrigerators');
            $table->enum('schedule', ['Mañana','Mediodía','Noche']);
            $table->string('image_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('closing_logs');
    }
};
