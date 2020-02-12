<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTable extends Migration
{

    public function up()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {

                $table->unsignedBigInteger('status_id')
                    ->after('id')
                    ->nullable()
                    ->default(null);

                $table->text('settings')
                    ->nullable()
                    ->default(null)
                    ->after('email');

                $table->string('api_token', 80)
                    ->after('password')
                    ->unique()
                    ->nullable()
                    ->default(null);

                $table->softDeletes();

                $table->foreign('status_id')
                    ->references('id')
                    ->on('user_statuses')
                    ->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        //
    }
}
