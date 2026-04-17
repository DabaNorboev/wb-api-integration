<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Console\OutputStyle;

class ApiFetcherService
{
    protected string $baseUrl = 'http://109.73.206.144:6969/api';
    protected string $apiKey = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';
    protected int $limit = 500;

    public function fetch(
        string $endpoint,
        string $model,
        array $uniqueKeys,
        array $params,
        OutputStyle $output
    ): int {
        $page = 1;
        $totalFetched = 0;
        $now = now();

        $output->progressStart();

        do {
            $items = $this->fetchPage($endpoint, $params, $page);

            if ($items === null) {
                $output->error("Failed to fetch page {$page}");
                break;
            }

            if (!empty($items)) {
                $this->saveChunks($model, $items, $uniqueKeys, $now);
                $totalFetched += count($items);
                $output->progressAdvance(count($items));
            }

            $page++;
            $totalPages = $this->getTotalPages($endpoint, $params);

        } while ($page <= $totalPages);

        $output->progressFinish();

        return $totalFetched;
    }

    protected function fetchPage(string $endpoint, array $params, int $page): ?array
    {
        $response = Http::timeout(30)->get("{$this->baseUrl}/{$endpoint}", array_merge($params, [
            'page' => $page,
            'limit' => $this->limit,
            'key' => $this->apiKey,
        ]));

        if (!$response->successful()) {
            return null;
        }

        return $response->json()['data'] ?? [];
    }

    protected function saveChunks(string $model, array $items, array $uniqueKeys, $now): void
    {
        $chunks = collect($items)
            ->map(fn($item) => array_merge($item, [
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

    protected function getTotalPages(string $endpoint, array $params): int
    {
        static $total = null;

        if ($total !== null) {
            return $total;
        }

        $response = Http::get("{$this->baseUrl}/{$endpoint}", array_merge($params, [
            'page' => 1,
            'limit' => 1,
            'key' => $this->apiKey,
        ]));

        $total = ceil(($response->json()['meta']['total'] ?? 0) / $this->limit);

        return $total;
    }
}
