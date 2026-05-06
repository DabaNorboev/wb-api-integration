<?php

namespace App\Console\Commands;

use App\Models\Sale;

class FetchSalesCommand extends BaseFetchCommand
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
