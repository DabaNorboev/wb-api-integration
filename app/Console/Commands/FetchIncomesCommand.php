<?php

namespace App\Console\Commands;

use App\Models\Income;

class FetchIncomesCommand extends BaseFetchCommand
{
    protected $signature = 'fetch:incomes {--days=120}';
    protected $description = 'Fetch incomes data from API by default for last 120 days';

    protected function getEndpoint(): string
    {
        return 'incomes';
    }

    protected function getModel(): string
    {
        return Income::class;
    }

    protected function getUniqueKeyFields(): array
    {
        return ['income_id', 'account_id'];
    }

    protected function getApiParams(): array
    {
        $days = $this->option('days');
        return [
            'dateFrom' => now()->subDays($days)->format('Y-m-d'),
            'dateTo' => now()->format('Y-m-d'),
        ];
    }
    protected function getServiceName(): string
    {
        return 'wildberries';
    }
}
