<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('closing_logs', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('establishment_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('refrigerator_id');
            $table->string('schedule')->nullable();
            $table->string('image_url');
            $table->timestamps();
            $table->foreign('establishment_id')->references('id')->on('establishments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('refrigerator_id')->references('id')->on('refrigerators')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('closing_logs');
    }
};