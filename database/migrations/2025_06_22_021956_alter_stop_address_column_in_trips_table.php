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
        Schema::table('trips', function (Blueprint $table) {
            $table->text('stop_address')->change();
        });
        // Schema::table('trips', function (Blueprint $table) {
        //     $table->dropColumn('stop_address');
        // });

        // Schema::table('users', function (Blueprint $table) {
        //     $table->text('stop_address')->nullable()->after('start_address');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->string('stop_address', 255)->change();
        });
    }
};
