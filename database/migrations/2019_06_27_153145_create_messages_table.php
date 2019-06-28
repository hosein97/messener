<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
       $table->increments('id');
       $table->integer('user_id')->unsigned();
       $table->foreign('user_id')->references('id')->on('users');
       $table->integer('chat_id')->unsigned();
       $table->foreign('chat_id')->references('id')->on('chats');
       $table->text('text');
       $table->timestamps();   
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
