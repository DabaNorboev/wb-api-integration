<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\ApiService;
use App\Models\Token;
use App\Models\TokenType;
use Illuminate\Console\Command;

class AddToken extends BaseAddCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'добавить токен';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $accountId = $this->chooseFromModel(Account::class, 'name', 'выберите аккаунт');
        $serviceId = $this->chooseFromModel(ApiService::class, 'name', 'выберите api сервис');

        $tokenTypes = TokenType::where('api_service_id', $serviceId)
            ->pluck('type', 'id')
            ->toArray();

        $chosenType = $this->choice('выберите тип токена', $tokenTypes);
        $tokenTypeId = array_search($chosenType, $tokenTypes);

        $value = $this->askRequired('введите значение токена');

        Token::updateOrCreate(
            [
                'account_id' => $accountId,
                'api_service_id' => $serviceId,
            ],
            [
                'token_type_id' => $tokenTypeId,
                'value' => $value,
            ]
        );

        $this->info('токен сохранён');
    }
}
