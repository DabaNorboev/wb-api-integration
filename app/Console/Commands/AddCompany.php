<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;

class AddCompany extends BaseAddCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'добавить компанию';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->askRequired('название компании');
        $description = $this->askOptional('описание (необязательно)');

        Company::create([
            'name' => $name,
            'description' => $description,
        ]);

        $this->info("компания '{$name}' добавлена");
    }
}
