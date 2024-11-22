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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('password');
            $table->string('happy_place')->nullable();
            $table->string('country')->nullable();
            $table->string('rolla_username')->nullable();
            $table->string('hear_rolla')->nullable();
            $table->string('photo')->nullable();
            $table->string('bio')->nullable();
            $table->integer('state_staus')->default(0);
            $table->string('following_user_id')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
