<?php

namespace App\Console\Commands\Wildberries;

use App\Console\Commands\BaseFetchCommand;
use App\Models\Sale;

class FetchSalesCommand extends WildberriesFetchCommand
{
    protected $signature = 'fetch:sales {--days=120}';
    protected $description = 'Fetch sales data from API by default for last 120 days';

    protected function getEndpoint(): string
    {
        return 'sales';
    }

    protected function getModel(): string
    {
        return Sale::class;
    }

    protected function getUniqueKeyFields(): array
    {
        return ['sale_id', 'account_id'];
    }

    protected function getApiParams(int $accountId): array
    {
        $maxDate = Sale::where('account_id', $accountId)->max('date');

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
