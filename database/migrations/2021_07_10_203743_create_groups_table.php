<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->tinyText('name');
            $table->tinyText('link');
            $table->string('platform');
            $table->string('type');
            $table->enum('privacy', ['Public', 'Private', 'Delisted']);
            $table->text('description');
            $table->bigInteger('creator_id')->unsigned();
            $table->bigInteger('size')->unsigned();
            $table->timestamps();
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
