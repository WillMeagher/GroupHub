<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('account_created')->default(0);
            $table->string('gender')->nullable();
            $table->string('school')->nullable();
            $table->string('major')->nullable();
            $table->string('year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('account_created');
            $table->dropColumn('gender');
            $table->dropColumn('school');
            $table->dropColumn('major');
            $table->dropColumn('year');
        });
    }
}