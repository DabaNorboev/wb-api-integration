<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\ApiService;
use App\Models\Company;
use App\Models\Token;
use App\Models\TokenType;
use Illuminate\Console\Command;

class MockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'создание тестовой компании, аккаунта, апи-сервиса, типа токена и токена для теста';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $company = Company::create([
            'name' => 'тестовая компания',
        ]);

        $account = Account::create([
            'company_id' => $company->id,
            'name' => 'тестовый аккаунт',
        ]);

        $apiService = ApiService::create([
            'name' => 'wildberries',
            'base_url' => 'http://109.73.206.144:6969/api',
        ]);

        $tokenType = TokenType::create([
            'api_service_id' => $apiService->id,
            'type' => 'api-key',
        ]);

        Token::create([
            'account_id' => $account->id,
            'api_service_id' => $apiService->id,
            'token_type_id' => $tokenType->id,
            'value' => 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie',
        ]);

        $this->info('успешно');
    }
}
