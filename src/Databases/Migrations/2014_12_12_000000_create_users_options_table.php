<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersOptionsTable extends Migration
{

    public function up()
    {
        if (!Schema::hasTable('option_user')) {

            Schema::create('option_user', function (Blueprint $table) {
                $table->unsignedBigInteger('option_id');
                $table->unsignedBigInteger('user_id');

                $table->foreign('option_id')
                    ->references('id')
                    ->on('options')
                    ->onDelete('cascade');

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            });

        }
    }

    public function down()
    {
        Schema::dropIfExists('option_user');
    }
}
