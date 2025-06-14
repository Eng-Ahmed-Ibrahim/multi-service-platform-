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
        Schema::create('cancelation_reasons', function (Blueprint $table) {
            $table->id();
            $table->string("reason")->nullable();
            $table->string("reason_ar")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations. wFGeCTiJoOo2
     */
    public function down(): void
    {
        Schema::dropIfExists('cancelation_reasons');
    }
};
