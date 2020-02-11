<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTable extends Migration
{

    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')
                ->after('id');
            $table->text('settings')
                ->nullable()
                ->default(null)
                ->after('email');
            $table->string('api_token', 80)
                ->unique()
                ->nullable()
                ->default(null)
                ->after('settings');
            $table->softDeletes();

            $table->foreign('status_id')
                ->references('id')
                ->on('user_statuses')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        //
    }
}
