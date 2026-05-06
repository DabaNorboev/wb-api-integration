<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Console\OutputStyle;

class ApiFetcherService
{
    protected string $baseUrl = '';
    protected string $value = '';
    protected int $limit = 500;

    public function setValue(string $value): static
    {
        $this->value = $value;
        return $this;
    }
    public function setBaseUrl(string $baseUrl): static
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function fetch(string $endpoint, string $model, array $uniqueKeys, array $params, OutputStyle $output, int $accountId): int
    {
        if (!$this->baseUrl || !$this->value)
        {
            throw new \RuntimeException('base_url или значение токена не было найдено');
        }

        $now = now();
        $totalFetched = 0;

        $firstPage = $this->fetchPage($endpoint, $params, 1);

        if ($firstPage === null) {
            $output->error("Failed to fetch first page");
            return 0;
        }

        $totalPages = ceil(($firstPage['meta']['total'] ?? 0) / $this->limit);

        $output->progressStart($firstPage['meta']['total'] ?? 0);

        $items = $firstPage['data'] ?? [];
        if (!empty($items)) {
            $this->saveChunks($model, $items, $uniqueKeys, $now, $accountId);
            $totalFetched += count($items);
            $output->progressAdvance(count($items));
        }

        for ($page = 2; $page <= $totalPages; $page++) {
            $response = $this->fetchPage($endpoint, $params, $page);

            if ($response === null) {
                $output->error("Failed to fetch page {$page}");
                break;
            }

            $items = $response['data'] ?? [];
            if (!empty($items)) {
                $this->saveChunks($model, $items, $uniqueKeys, $now, $accountId);
                $totalFetched += count($items);
                $output->progressAdvance(count($items));
            }
        }

        $output->progressFinish();

        return $totalFetched;
    }

    protected function fetchPage(string $endpoint, array $params, int $page): ?array
    {
        $response = Http::timeout(30)->get("{$this->baseUrl}/{$endpoint}", array_merge($params, [
            'page' => $page,
            'limit' => $this->limit,
            'key' => $this->value,
        ]));

        if (!$response->successful()) {
            return null;
        }

        return $response->json();
    }

    protected function saveChunks(string $model, array $items, array $uniqueKeys, $now, int $accountId): void
    {
        $chunks = collect($items)
            ->map(fn($item) => array_merge($item, [
                'account_id' => $accountId,
                'created_at' => $now,
                'updated_at' => $now,
            ]))
            ->chunk(500);

        foreach ($chunks as $chunk) {
            $model::upsert(
                $chunk->toArray(),
                $uniqueKeys,
                array_keys($chunk->first())
            );
        }
    }
}
