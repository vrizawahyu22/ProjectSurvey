<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TokenManagement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('token_management', function(Blueprint $tb){
            $tb->increments('id');
            $tb->integer('member_username'); //related to table users
            $tb->string('access_token');
            $tb->datetime('expired_at');
            $tb->tinyinteger('is_active')->nullable();
            $tb->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('token_management');
    }
}
