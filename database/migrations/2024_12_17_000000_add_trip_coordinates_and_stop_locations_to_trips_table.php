<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->json('trip_coordinates')->nullable()->change();
            $table->json('stop_locations')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->text('trip_coordinates')->nullable()->change();
            $table->text('stop_locations')->nullable()->change();
        });
    }
};
