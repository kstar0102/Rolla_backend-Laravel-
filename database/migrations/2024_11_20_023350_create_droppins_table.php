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
        Schema::create('droppins', function (Blueprint $table) {
            $table->id();
            $table->integer('trip_id');
            $table->integer('stop_index');
            $table->string('image_path');
            $table->string('image_caption');
            $table->string('likes_user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('droppins');
    }
};
