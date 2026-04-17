<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestApiEndpoints extends Command
{
    protected $signature = 'api:test';
    protected $description = 'Test API endpoints and show response structure';

    protected $baseUrl = 'http://109.73.206.144:6969/api';
    protected $apiKey = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';

    public function handle()
    {
        $endpoints = [
            'sales',
            'orders',
            'stocks',
            'incomes',
        ];

        foreach ($endpoints as $endpoint) {
            $this->info("-{$endpoint}-");

            $params = [
                'dateFrom' => now()->subDays(14)->format('Y-m-d'),
                'dateTo' => now()->format('Y-m-d'),
                'limit' => 1,
                'key' => $this->apiKey,
            ];

            if ($endpoint === 'stocks') {
                $params['dateFrom'] = now()->format('Y-m-d');
                unset($params['dateTo']);
            }

            $response = Http::get("{$this->baseUrl}/{$endpoint}", $params);

            if ($response->successful()) {
                $data = $response->json();
                $this->info("Status: OK");
                $this->info("Total records: " . ($data['meta']['total'] ?? 'N/A'));

                if (!empty($data['data'][0])) {
                    $this->info("First record fields:");
                    $this->line(json_encode($data['data'][0], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                }
            } else {
                $this->error("Failed: " . $response->status());
                $this->error($response->body());
            }
        }
    }
}
