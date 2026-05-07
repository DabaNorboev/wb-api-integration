<?php

namespace App\Console\Commands;

use App\Models\ApiService;
use Illuminate\Console\Command;

class AddApiService extends BaseAddCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:api-service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'добавить api сервис';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('название сервиса');
        $baseUrl = $this->ask('base url');

        ApiService::create([
            'name' => $name,
            'base_url' => $baseUrl,
        ]);

        $this->info("сервис '{$name}' добавлен");
    }
}
