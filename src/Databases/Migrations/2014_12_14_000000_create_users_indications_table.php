<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersIndicationsTable extends Migration
{

    public function up()
    {
        if (!Schema::hasTable('user_indications')) {

            Schema::create('user_indications', function (Blueprint $table) {
                $table->unsignedBigInteger('indicated_id');
                $table->unsignedBigInteger('user_id');

                $table->foreign('indicated_id')
                    ->references('id')
                    ->on('users')
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
        Schema::dropIfExists('user_indications');
    }
}
