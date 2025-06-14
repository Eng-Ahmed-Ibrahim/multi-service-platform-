<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('lat', 10, 8)->nullable()->after('current_loggin');
            $table->decimal('lng', 11, 8)->nullable()->after('lat');
            $table->timestamp('last_seen')->nullable()->after('lng');

        });

        Schema::table('providers', function (Blueprint $table) {
            $table->decimal('lat', 10, 8)->nullable()->after('current_loggin');
            $table->decimal('lng', 11, 8)->nullable()->after('lat');
            $table->timestamp('last_seen')->nullable()->after('lng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lng']);
            $table->dropColumn('last_seen');
        });

        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lng']);
            $table->dropColumn('last_seen');

        });
    }
};
