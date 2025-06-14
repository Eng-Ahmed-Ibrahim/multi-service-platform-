<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Reviewer
            $table->foreignId('provider_id')->constrained('providers')->onDelete('cascade'); // Reviewed provider
            $table->tinyInteger('rating')->unsigned()->comment('Rating from 1 to 5'); // Rating field
            $table->text('message')->nullable(); // Review message
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
