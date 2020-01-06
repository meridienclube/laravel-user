<?php

use App\Permission;
use App\Role;
use App\Status;
use App\Step;
use App\User;
use App\Option;
use App\UserContactType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateUserTables();
        $this->createUsers();
    }

    private function createUsers()
    {

    }

    private function truncateUserTables()
    {
        Schema::disableForeignKeyConstraints();
        $this->command->info('Fazendo um truncate nas tabelas de usuarios, sai da frente... ;/');
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();
        $this->command->info('Pronto, truncates de usuarios feitos, acho que com sucesso :D');
    }
}
