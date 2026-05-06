<?php

namespace App\Console\Commands\Wildberries;

use App\Models\Income;

class FetchIncomesCommand extends WildberriesFetchCommand
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

    protected function getApiParams(int $accountId): array
    {
        $maxDate = Income::where('account_id', $accountId)->max('date');

        $days = $this->option('days') ?? 120;

        $dateFrom = $maxDate
            ? now()->parse($maxDate)->format('Y-m-d')
            : now()->subDays((int)$days)->format('Y-m-d');

        return [
            'dateFrom' => $dateFrom,
            'dateTo'   => now()->format('Y-m-d'),
        ];
    }
}
