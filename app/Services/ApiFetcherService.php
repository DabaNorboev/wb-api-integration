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
//        $this->baseUrl = '';
        if (!$this->baseUrl || !$this->value) {
            $output->error("base_url или значение токена не было найдено");
            return 0;
        }

        $now = now();
        $totalFetched = 0;

        $firstPage = $this->fetchPage($endpoint, $params, 1, $output);

        if ($firstPage === null) {
            $output->error("не удалось загрузить первую страницу");
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
            $response = $this->fetchPage($endpoint, $params, $page, $output);

            if ($response === null) {
                $output->error("не удалось загрузить страницу {$page}");
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

    protected function fetchPage(string $endpoint, array $params, int $page, OutputStyle $output, int $retries = 3): ?array
    {
        for ($attempt = 1; $attempt <= $retries; $attempt++) {
            $response = Http::timeout(30)->get("{$this->baseUrl}/{$endpoint}", array_merge($params, [
                'page'  => $page,
                'limit' => $this->limit,
                'key'   => $this->value,
            ]));

            if ($response->status() === 429) {
                $retryAfter = (int) ($response->header('X-Ratelimit-Retry') ?? 0);

                if ($retryAfter === 0) {
                    $retryAfter = 5 * $attempt;
                }

                $output->warning("429, попытка {$attempt}/{$retries}, пауза {$retryAfter} сек (X-Ratelimit-Retry: {$retryAfter})");
                sleep($retryAfter);
                continue;
            }

            if ($response->status() === 401) {
                $output->error("401, проверьте токен");
                return null;
            }

            if ($response->status() === 403) {
                $output->error("403, недостаточно прав у токена");
                return null;
            }

            if (!$response->successful()) {
                $output->error("ошибка {$response->status()} на странице {$page}");
                return null;
            }

            $remaining = (int) $response->header('X-Ratelimit-Remaining', 0);

            if ($remaining === 0) {
                $output->warning("лимит запросов исчерпан, пауза 5 сек");
                sleep(5);
            }

            return $response->json();
        }

        $output->error("не удалось получить страницу {$page} после {$retries} попыток");
        return null;
    }

    protected function saveChunks(string $model, array $items, array $uniqueKeys, string $now, int $accountId): void
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
