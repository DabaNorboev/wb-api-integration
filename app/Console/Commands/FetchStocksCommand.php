<?php

namespace App\Console\Commands;

use App\Models\Stock;

class FetchStocksCommand extends BaseFetchCommand
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
        return ['barcode', 'warehouse_name', 'date'];
    }

    protected function getApiParams(): array
    {
        return [
            'dateFrom' => now()->format('Y-m-d'),
        ];
    }
}
