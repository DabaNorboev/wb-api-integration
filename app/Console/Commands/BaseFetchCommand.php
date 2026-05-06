<?php

namespace App\Console\Commands;

use App\Models\Token;
use Illuminate\Console\Command;
use App\Services\ApiFetcherService;

abstract class BaseFetchCommand extends Command
{
    abstract protected function getEndpoint(): string;
    abstract protected function getModel(): string;
    abstract protected function getUniqueKeyFields(): array;
    abstract protected function getApiParams(): array;
    abstract protected function getServiceName(): string;

    public function handle(ApiFetcherService $fetcher)
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

            $total = $fetcher->fetch($this->getEndpoint(), $this->getModel(),
                $this->getUniqueKeyFields(), $this->getApiParams(),
                $this->output, $token->account_id);

            $this->info("Total fetched: {$total}");
        }
    }
}
