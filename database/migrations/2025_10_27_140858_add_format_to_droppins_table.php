<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('droppins', function (Blueprint $table) {
            $table->string('format')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('droppins', function (Blueprint $table) {
            $table->dropColumn('format');
        });
    }
};
