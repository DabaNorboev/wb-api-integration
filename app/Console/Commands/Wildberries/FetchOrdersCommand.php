<?php

namespace App\Console\Commands\Wildberries;

use App\Models\Order;

class FetchOrdersCommand extends WildberriesFetchCommand
{
    protected $signature = 'fetch:orders {--days=120}';

    protected $description = 'Fetch orders data from API by default for last {$period} days';

    protected function getEndpoint(): string
    {
        return 'orders';
    }

    protected function getModel(): string
    {
        return Order::class;
    }

    protected function getUniqueKeyFields(): array
    {
        return ['g_number', 'account_id'];
    }
    protected function getApiParams(int $accountId): array
    {
        $maxDate = Order::where('account_id', $accountId)->max('date');

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
