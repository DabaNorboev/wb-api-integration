<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\ApiService;
use App\Models\Company;
use App\Models\Token;
use App\Models\TokenType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $apiService = ApiService::create([
            'name' => 'wildberries',
            'base_url' => 'http://109.73.206.144:6969/api',
        ]);

        $tokenType = TokenType::create([
            'api_service_id' => $apiService->id,
            'type' => 'api-key',
        ]);

        $company = Company::create([
            'name' => 'тестовая компания',
        ]);

        $count = 5;

        for ($i = 1; $i <= $count; $i++) {
            $account = Account::create([
                'company_id' => $company->id,
                'name' => "аккаунт {$i}",
            ]);

            Token::create([
                'account_id' => $account->id,
                'api_service_id' => $apiService->id,
                'token_type_id' => $tokenType->id,
                'value' => 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie',
//                'value' => 'test401test401'
            ]);
        }
    }
}
