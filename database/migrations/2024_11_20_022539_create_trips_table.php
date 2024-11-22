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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('start_address');
            $table->string('stop_address');
            $table->string('destination_address');
            $table->timestamp('trip_start_date')->nullable();
            $table->timestamp('trip_end_date')->nullable();
            $table->string('trip_miles');
            $table->string('trip_sound');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
