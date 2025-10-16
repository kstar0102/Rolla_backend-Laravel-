<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->longText('token')->change(); // MySQL LONGTEXT (~4GB)
        });
    }
    public function down(): void
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->string('token', 255)->change();
        });
    }
};
