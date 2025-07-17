<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('droppins', function (Blueprint $table) {
            $table->integer('viewed_count')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('droppins', function (Blueprint $table) {
            $table->dropColumn('viewed_count');
        });
    }
};
