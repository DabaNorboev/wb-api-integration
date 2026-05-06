<?php

namespace App\Console\Commands\Wildberries;


use App\Models\Stock;

class FetchStocksCommand extends WildberriesFetchCommand
{
    protected $signature = 'fetch:stocks';
    protected $description = 'Fetch stocks data from API for today';

    protected function getEndpoint(): string
    {
        return 'stocks';
    }
    protected function getModel(): string
    {
        return Stock::class;
    }
    protected function getUniqueKeyFields(): array
    {
        return ['barcode', 'warehouse_name', 'date', 'account_id'];
    }

    protected function getApiParams(int $accountId): array
    {
        return [
            'dateFrom' => now()->format('Y-m-d'),
        ];
    }
}
