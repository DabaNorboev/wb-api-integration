<?php

namespace App\Console\Commands;

use App\Models\Token;
use App\Services\ApiFetcherService;
use App\Services\BatchUpsertService;
use App\Services\DataMapperService;
use Illuminate\Console\Command;

abstract class BaseFetchCommand extends Command
{
    abstract protected function getEndpoint(): string;
    abstract protected function getModel(): string;
    abstract protected function getUniqueKeyFields(): array;
    abstract protected function getApiParams(int $accountId): array;
    abstract protected function getServiceName(): string;

    public function handle(ApiFetcherService $fetcher, DataMapperService $mapper, BatchUpsertService $upserter)
    {
        $tokens = Token::whereHas('apiService', fn($q) =>
        $q->where('name', $this->getServiceName())
        )->with(['account', 'apiService'])->get();

        if ($tokens->isEmpty()) {
            $this->error('токены не найдены');
            return;
        }

        foreach ($tokens as $token) {
            $this->info("Fetching {$this->getEndpoint()} for account: {$token->account->name}");

            $fetcher->setBaseUrl($token->apiService->base_url);
            $fetcher->setValue($token->value);

            $model = $this->getModel();
            $uniqueKeys = $this->getUniqueKeyFields();
            $now = now();
            $accountId = $token->account_id;

            $total = $fetcher->fetchPages(
                $this->getEndpoint(),
                $this->getApiParams($accountId),
                $this->output,
                function (array $items) use ($mapper, $upserter, $model, $uniqueKeys, $now, $accountId) {
                    $mapped = $mapper->map($items, $accountId, $now);
                    $upserter->upsert($model, $mapped, $uniqueKeys);
                }
            );

            $this->info("Total fetched: {$total}");
        }
    }
}
