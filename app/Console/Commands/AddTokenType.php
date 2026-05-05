<?php

namespace App\Console\Commands;

use App\Models\ApiService;
use App\Models\TokenType;
use Illuminate\Console\Command;

class AddTokenType extends BaseAddCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add-token-type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'добавить тип токена';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $serviceId = $this->chooseFromModel(ApiService::class, 'name', 'выберите api сервис');
        $type = $this->askRequired('тип токена (bearer, api-key, login_password)');

        TokenType::create([
            'api_service_id' => $serviceId,
            'type' => $type,
        ]);

        $this->info("тип токена '{$type}' добавлен");
    }
}
