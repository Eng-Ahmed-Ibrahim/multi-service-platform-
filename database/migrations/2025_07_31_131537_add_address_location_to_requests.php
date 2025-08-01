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
        Schema::table('requests', function (Blueprint $table) {
            $table->string('pickup_address_ar')->nullable();
            $table->string('pickup_address_en')->nullable();
            $table->string('dropoff_address_ar')->nullable();
            $table->string('dropoff_address_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn(['pickup_address_ar', 'pickup_address_en', 'dropoff_address_ar', 'dropoff_address_en']);
        });
    }
};
