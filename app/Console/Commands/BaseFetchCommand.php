<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApiFetcherService;

abstract class BaseFetchCommand extends Command
{
    abstract protected function getEndpoint(): string;
    abstract protected function getModel(): string;
    abstract protected function getUniqueKeyFields(): array;
    abstract protected function getApiParams(): array;

    public function handle(ApiFetcherService $fetcher)
    {
        $endpoint = $this->getEndpoint();

        $this->info("Fetching {$endpoint}...");

        $total = $fetcher->fetch(
            $endpoint,
            $this->getModel(),
            $this->getUniqueKeyFields(),
            $this->getApiParams(),
            $this->output
        );

        $this->info("Total {$endpoint} fetched: {$total}");
    }
}
