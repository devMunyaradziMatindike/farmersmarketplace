<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MbareMusikaScraper
{
    public function fetchAndStore(): int
    {
        $url = 'https://zimpricecheck.com/price-updates/fruit-and-vegetable-prices/';
        $resp = Http::timeout(15)->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (compatible; MusikaWeduBot/1.0; +https://musikawedu.co.zw)',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        ])->get($url);

        if (!$resp->ok()) {
            return 0;
        }

        $html = $resp->body();
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        $xpath = new \DOMXPath($dom);

        // Find the main prices table
        $tables = $xpath->query('//table');
        if ($tables->length === 0) {
            return 0;
        }

        $prices = [];
        $priceDate = now()->toDateString();

        foreach ($tables as $table) {
            $rows = $xpath->query('.//tr', $table);
            if ($rows->length < 2) {
                continue;
            }

            // Skip header row, process data rows
            for ($i = 1; $i < $rows->length; $i++) {
                $cells = $xpath->query('.//td', $rows->item($i));
                if ($cells->length < 4) {
                    continue;
                }

                $item = trim($cells->item(0)->textContent);
                $quantity = trim($cells->item(1)->textContent);
                $priceUsd = $this->parsePrice($cells->item(2)->textContent);
                $priceZig = $this->parsePrice($cells->item(3)->textContent);

                if ($item && $priceUsd !== null) {
                    $prices[] = [
                        'item' => $item,
                        'quantity' => $quantity,
                        'price_usd' => $priceUsd,
                        'price_zig' => $priceZig,
                        'market' => 'Mbare Musika',
                        'province' => 'Harare',
                        'category' => 'Fruits & Vegetables',
                        'scraped_at' => now()->toISOString(),
                        'price_date' => $priceDate,
                    ];
                }
            }
            break; // Only process first table
        }

        // Store in JSON file
        $filename = "market_prices/mbare_musika_{$priceDate}.json";
        Storage::disk('local')->put($filename, json_encode($prices, JSON_PRETTY_PRINT));

        // Also store latest prices for quick access
        Storage::disk('local')->put('market_prices/latest_mbare_musika.json', json_encode($prices, JSON_PRETTY_PRINT));

        return count($prices);
    }

    private function parsePrice(string $value): ?float
    {
        $value = str_replace(['US$', 'ZIG', ' ', ','], '', $value);
        return is_numeric($value) ? (float)$value : null;
    }
}
