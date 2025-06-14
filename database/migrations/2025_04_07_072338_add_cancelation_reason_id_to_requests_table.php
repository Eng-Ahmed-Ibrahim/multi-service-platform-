<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->unsignedBigInteger('cancelation_reason_id')->nullable()->after('id'); // adjust position with 'after()' if needed
    
            // If you have a cancelation_reasons table and want to set a foreign key:
            $table->foreign('cancelation_reason_id')
                  ->references('id')
                  ->on('cancelation_reasons')
                  ->onDelete('set null');
        });
    }
    
    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['cancelation_reason_id']);
            $table->dropColumn('cancelation_reason_id');
        });
    }
    
};
