<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trips', function (Blueprint $table) {
            if (Schema::hasColumn('trips', 'trip_coordinates')) {
                $table->json('trip_coordinates')->nullable()->change();
            } else {
                $table->json('trip_coordinates')->nullable();
            }

            if (Schema::hasColumn('trips', 'stop_locations')) {
                $table->json('stop_locations')->nullable()->change();
            } else {
                $table->json('stop_locations')->nullable();
            }

            if (Schema::hasColumn('trips', 'destination_text_address')) {
                $table->json('destination_text_address')->nullable()->change();
            } else {
                $table->json('destination_text_address')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('trips', function (Blueprint $table) {
            if (Schema::hasColumn('trips', 'trip_coordinates')) {
                $table->text('trip_coordinates')->nullable()->change();
            }

            if (Schema::hasColumn('trips', 'stop_locations')) {
                $table->text('stop_locations')->nullable()->change();
            }
        });
    }
};
