<?php

namespace App\Services;

class DataMapperService
{
    public function map(array $items, int $accountId, string $now): array
    {
        return array_map(fn($item) => array_merge($item, [
            'account_id' => $accountId,
            'created_at' => $now,
            'updated_at' => $now,
        ]), $items);
    }
}
