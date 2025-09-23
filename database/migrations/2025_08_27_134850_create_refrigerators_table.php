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
        Schema::create('refrigerators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('establishment_id')->constrained('establishments');
            $table->float('temperature');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refrigerators');
    }
};
<<<<<<< HEAD

// 2025_09_21_003054_create_refrigerators_table
=======
>>>>>>> origin/development
