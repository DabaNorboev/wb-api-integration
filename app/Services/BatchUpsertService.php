<?php

namespace App\Services;

class BatchUpsertService
{
    protected int $chunkSize = 500;

    public function upsert(string $model, array $items, array $uniqueKeys): void
    {
        foreach (array_chunk($items, $this->chunkSize) as $chunk) {
            $model::upsert(
                $chunk,
                $uniqueKeys,
                array_keys($chunk[0])
            );
        }
    }
}
