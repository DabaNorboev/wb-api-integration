<?php

namespace App\Console\Commands;

use App\Models\Order;

class FetchOrdersCommand extends BaseFetchCommand
{
    protected $signature = 'fetch:orders {--days=30}';

    protected $description = 'Fetch orders data from API by default for last 30 days';

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
        return ['g_number'];
    }

    protected function getApiParams(): array
    {
        $days = $this->option('days');
        return [
            'dateFrom' => now()->subDays($days)->format('Y-m-d'),
            'dateTo' => now()->format('Y-m-d'),
        ];
    }
}
