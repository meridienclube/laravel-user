<?php

namespace ConfrariaWeb\User\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class CheckPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check-package';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check important things about the package';

    protected $tables = [];
    protected $allRight;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->allRight = true;
        $this->tables = ['users' => ['id', 'status_id', 'name', 'email', 'settings', 'api_token',]];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$this->info('<fg=red;bg=yellow>Importante:</>');
        //$this->info('<fg=blue>Se precisar criar tabelas e/ou colunas verifique em migrations qual o formato correto de criação de cada uma...</>');
        $this->info('Analizando...');

        foreach ($this->tables as $table => $columns) {
            $this->checkAndCreateTable($table);
            foreach ($columns as $column) {
                $this->checkAndCreateColumn($table, $column);
            }
        }

        if ($this->allRight) {
            $this->info('<fg=black;bg=white>Esta tudo certinho aparentemente...</>');
        } else {
            $this->info('<fg=red;bg=white>Algo errado não esta certo, verifique...</>');
        }
        $this->info('Finalizado...');
    }

    private function checkAndCreateTable($table)
    {
        if (!Schema::hasTable($table)) {
            $this->allRight = false;
            $this->info('<fg=red;bg=white># A tabela ' . $table . ' não existe...</>');
        }
    }

    private function checkAndCreateColumn($table, $column)
    {
        if (!Schema::hasColumn($table, $column)) {
            $this->allRight = false;
            $this->info('<fg=red;bg=white>### A coluna ' . $column . ' na tabela ' . $table . ' não existe...</>');
        }
    }
}
