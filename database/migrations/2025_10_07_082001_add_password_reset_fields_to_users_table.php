<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('reset_code_hash')->nullable();
            $table->timestamp('reset_code_expires_at')->nullable();
            $table->unsignedTinyInteger('reset_code_attempts')->default(0);
            $table->timestamp('reset_code_last_sent_at')->nullable();

            // optional short-lived token used after code verification
            $table->string('reset_token', 100)->nullable();
            $table->timestamp('reset_token_expires_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'reset_code_hash',
                'reset_code_expires_at',
                'reset_code_attempts',
                'reset_code_last_sent_at',
                'reset_token',
                'reset_token_expires_at',
            ]);
        });
    }
};
