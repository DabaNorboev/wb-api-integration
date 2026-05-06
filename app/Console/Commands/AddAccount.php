<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Company;
use Illuminate\Console\Command;

class AddAccount extends BaseAddCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'добавить аккаунт';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companyId = $this->chooseFromModel(Company::class, 'name', 'выберите компанию');
        $name = $this->askRequired('имя для аккаунта');

        Account::create([
            'company_id' => $companyId,
            'name' => $name,
        ]);

        $this->info("аккаунт '{$name}' добавлен");
    }
}
