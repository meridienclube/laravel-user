<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTable extends Migration
{

    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')->after('id');
            //$table->string('cpf_cnpj')->unique()->nullable()->default(null)->after('email');
            $table->text('settings')->nullable()->default(null)->after('cpf_cnpj');
            $table->string('api_token', 80)->unique()->nullable()->default(null)->after('settings');
            $table->softDeletes();

            $table->foreign('status_id')
                ->references('id')
                ->on('statuses')
                ->onDelete('cascade');
        });

        Schema::create('user_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->integer('order')->default(1);
            $table->integer('closure')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('order')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('step_user', function (Blueprint $table) {
            $table->unsignedBigInteger('step_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('step_id')
                ->references('id')
                ->on('steps')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->primary(['step_id', 'user_id']);
        });
    }

    public function down()
    {
        //
    }
}
