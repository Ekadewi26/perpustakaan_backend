<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('gender', ['pria', 'wanita', 'lainnya'])->nullable()->after('password');
            $table->date('birthdate')->nullable()->after('gender');
            $table->string('phone', 15)->nullable()->after('birthdate');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gender', 'birthdate', 'phone']);
        });
    }
};
