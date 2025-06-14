<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBalanceToUsersAndProviders extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('balance', 10, 2)->default(0)->after('email'); // تقدر تغير مكان after لو حابب
        });

        Schema::table('providers', function (Blueprint $table) {
            $table->decimal('balance', 10, 2)->default(0)->after('email'); // نفس الكلام هنا
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('balance');
        });

        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn('balance');
        });
    }
}
