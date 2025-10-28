<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MultiCategoryPriceScraper
{
    private array $sources = [
        'fruits_vegetables' => [
            'url' => 'https://zimpricecheck.com/price-updates/fruit-and-vegetable-prices/',
            'category' => 'Fruits & Vegetables',
            'market' => 'Mbare Musika',
            'province' => 'Harare'
        ],
        'livestock' => [
            'url' => 'https://ccsales.co.zw/auction-results/national-bull-sale-averages/',
            'category' => 'Livestock',
            'market' => 'National Bull Sales',
            'province' => 'National'
        ],
        'chemicals' => [
            'url' => 'https://www.zbms.co.zw/product-category/agriculture/herbicides/',
            'category' => 'Agricultural Chemicals',
            'market' => 'ZBMS',
            'province' => 'National'
        ],
        'equipment' => [
            'url' => 'https://www.electrosales.co.zw/shop/catalog/agricultural',
            'category' => 'Agricultural Equipment',
            'market' => 'ElectroSales',
            'province' => 'National'
        ],
        'classifieds' => [
            'url' => 'https://www.classifieds.co.zw/zimbabwe-farming-agriculture',
            'category' => 'General Agriculture',
            'market' => 'Classifieds',
            'province' => 'National'
        ]
    ];

    public function getSources(): array
    {
        return $this->sources;
    }

    public function fetchAllCategories(): array
    {
        $results = [];
        
        foreach ($this->sources as $category => $config) {
            $results[$category] = $this->fetchCategoryPrices($category, $config);
        }
        
        return $results;
    }

    public function fetchCategoryPrices(string $category, array $config): int
    {
        try {
            $resp = Http::timeout(15)->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (compatible; MusikaWeduBot/1.0; +https://musikawedu.co.zw)',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ])->get($config['url']);

            if (!$resp->ok()) {
                return 0;
            }

            $html = $resp->body();
            $prices = $this->parseByCategory($category, $html, $config);
            
            if (empty($prices)) {
                return 0;
            }

            // Store category-specific data
            $this->storeCategoryData($category, $prices);
            
            return count($prices);
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function parseByCategory(string $category, string $html, array $config): array
    {
        switch ($category) {
            case 'fruits_vegetables':
                return $this->parseFruitsVegetables($html, $config);
            case 'livestock':
                return $this->parseLivestock($html, $config);
            case 'chemicals':
                return $this->parseChemicals($html, $config);
            case 'equipment':
                return $this->parseEquipment($html, $config);
            case 'classifieds':
                return $this->parseClassifieds($html, $config);
            default:
                return [];
        }
    }

    private function parseFruitsVegetables(string $html, array $config): array
    {
        // Existing Mbare Musika parsing logic
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        $xpath = new \DOMXPath($dom);

        $prices = [];
        $priceDate = now()->toDateString();

        $tables = $xpath->query('//table');
        foreach ($tables as $table) {
            $rows = $xpath->query('.//tr', $table);
            if ($rows->length < 2) continue;

            for ($i = 1; $i < $rows->length; $i++) {
                $cells = $xpath->query('.//td', $rows->item($i));
                if ($cells->length < 4) continue;

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
                        'market' => $config['market'],
                        'province' => $config['province'],
                        'category' => $config['category'],
                        'scraped_at' => now()->toISOString(),
                        'price_date' => $priceDate,
                    ];
                }
            }
            break;
        }

        return $prices;
    }

    private function parseLivestock(string $html, array $config): array
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        $xp = new \DOMXPath($dom);

        $prices = [];
        $date = now()->toDateString();

        // Try tables first
        $tables = $xp->query('//table');
        foreach ($tables as $table) {
            $rows = $xp->query('.//tr', $table);
            foreach ($rows as $row) {
                $cells = $xp->query('.//td', $row);
                if ($cells->length >= 2) {
                    $name = trim($cells->item(0)->textContent);
                    $rawPrice = trim($cells->item(1)->textContent);
                    $price = $this->parsePrice($rawPrice);
                    if ($name !== '' && $price !== null) {
                        $prices[] = [
                            'item' => $name,
                            'quantity' => 'per head',
                            'price_usd' => $price,
                            'price_zig' => null,
                            'market' => $config['market'],
                            'province' => $config['province'],
                            'category' => $config['category'],
                            'scraped_at' => now()->toISOString(),
                            'price_date' => $date,
                        ];
                    }
                }
            }
            if (!empty($prices)) { break; }
        }

        // Fallback: look for "average", "bull", numeric prices in text blocks
        if (empty($prices)) {
            $nodes = $xp->query('//p|//li|//div');
            foreach ($nodes as $node) {
                $txt = trim(preg_replace('/\s+/', ' ', $node->textContent));
                if ($txt === '' || stripos($txt, 'bull') === false) { continue; }
                if (preg_match('/(US\$|\$)\s*\d+[.,]?\d*/i', $txt, $m)) {
                    $prices[] = [
                        'item' => 'Bull (average)',
                        'quantity' => 'per head',
                        'price_usd' => $this->parsePrice($m[0]),
                        'price_zig' => null,
                        'market' => $config['market'],
                        'province' => $config['province'],
                        'category' => $config['category'],
                        'scraped_at' => now()->toISOString(),
                        'price_date' => $date,
                    ];
                }
            }
        }

        return $prices;
    }

    private function parseChemicals(string $html, array $config): array
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        $xp = new \DOMXPath($dom);

        $prices = [];
        $date = now()->toDateString();

        // WooCommerce-like product cards
        $cards = $xp->query('//*[contains(@class,"product")]');
        foreach ($cards as $card) {
            $nameNode = $xp->query('.//h2|.//h3|.//a[contains(@class,"woocommerce-loop-product__title")]|.//a[contains(@class,"title")]', $card)->item(0);
            $priceNode = $xp->query('.//*[contains(@class,"price")]', $card)->item(0);
            if (!$nameNode || !$priceNode) { continue; }

            $name = trim($nameNode->textContent);
            $raw = trim($priceNode->textContent);
            $price = $this->parsePrice($raw);
            if ($name !== '' && $price !== null) {
                $prices[] = [
                    'item' => $name,
                    'quantity' => 'per unit',
                    'price_usd' => $price,
                    'price_zig' => null,
                    'market' => $config['market'],
                    'province' => $config['province'],
                    'category' => $config['category'],
                    'scraped_at' => now()->toISOString(),
                    'price_date' => $date,
                ];
            }
        }

        return $prices;
    }

    private function parseEquipment(string $html, array $config): array
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        $xp = new \DOMXPath($dom);

        $prices = [];
        $date = now()->toDateString();

        // ElectroSales specific selectors
        $products = $xp->query('//div[contains(@class,"product-item") or contains(@class,"product") or contains(@class,"item")]');
        
        foreach ($products as $product) {
            // Try multiple title selectors
            $titleSelectors = [
                './/h3[contains(@class,"product-title")]',
                './/h2[contains(@class,"product-title")]',
                './/a[contains(@class,"product-title")]',
                './/h3',
                './/h2',
                './/a[contains(@href,"product")]'
            ];
            
            $titleNode = null;
            foreach ($titleSelectors as $selector) {
                $titleNode = $xp->query($selector, $product)->item(0);
                if ($titleNode) break;
            }
            
            // Try multiple price selectors
            $priceSelectors = [
                './/span[contains(@class,"price")]',
                './/div[contains(@class,"price")]',
                './/span[contains(@class,"amount")]',
                './/div[contains(@class,"amount")]',
                './/*[contains(text(),"$")]',
                './/*[contains(text(),"USD")]'
            ];
            
            $priceNode = null;
            foreach ($priceSelectors as $selector) {
                $priceNode = $xp->query($selector, $product)->item(0);
                if ($priceNode) break;
            }
            
            if ($titleNode && $priceNode) {
                $name = trim($titleNode->textContent);
                $raw = trim($priceNode->textContent);
                $price = $this->parsePrice($raw);
                
                if ($name !== '' && $price !== null) {
                    $prices[] = [
                        'item' => $name,
                        'quantity' => 'per unit',
                        'price_usd' => $price,
                        'price_zig' => null,
                        'market' => $config['market'],
                        'province' => $config['province'],
                        'category' => $config['category'],
                        'scraped_at' => now()->toISOString(),
                        'price_date' => $date,
                    ];
                }
            }
        }

        // Fallback: look for any text containing prices
        if (empty($prices)) {
            $allText = $xp->query('//text()[contains(.,"$") or contains(.,"USD")]');
            foreach ($allText as $textNode) {
                $text = trim($textNode->textContent);
                if (preg_match('/([A-Za-z\s]+).*?(\$[\d,]+\.?\d*)/', $text, $matches)) {
                    $name = trim($matches[1]);
                    $price = $this->parsePrice($matches[2]);
                    if ($name !== '' && $price !== null && strlen($name) > 3) {
                        $prices[] = [
                            'item' => $name,
                            'quantity' => 'per unit',
                            'price_usd' => $price,
                            'price_zig' => null,
                            'market' => $config['market'],
                            'province' => $config['province'],
                            'category' => $config['category'],
                            'scraped_at' => now()->toISOString(),
                            'price_date' => $date,
                        ];
                    }
                }
            }
        }

        return $prices;
    }

    private function parseClassifieds(string $html, array $config): array
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        $xp = new \DOMXPath($dom);

        $prices = [];
        $date = now()->toDateString();

        // Listing cards commonly have title + price pair
        $cards = $xp->query('//*[contains(@class,"listing") or contains(@class,"result") or contains(@class,"card")]');
        foreach ($cards as $card) {
            $titleNode = $xp->query('.//h2|.//h3|.//a[contains(@class,"title")]', $card)->item(0);
            $priceNode = $xp->query('.//*[contains(@class,"price")]', $card)->item(0);
            if (!$titleNode || !$priceNode) { continue; }

            $name = trim($titleNode->textContent);
            $raw = trim($priceNode->textContent);
            $price = $this->parsePrice($raw);
            if ($name !== '' && $price !== null) {
                $prices[] = [
                    'item' => $name,
                    'quantity' => 'per unit',
                    'price_usd' => $price,
                    'price_zig' => null,
                    'market' => $config['market'],
                    'province' => $config['province'],
                    'category' => $config['category'],
                    'scraped_at' => now()->toISOString(),
                    'price_date' => $date,
                ];
            }
        }

        // Fallback: look for any text containing prices and agricultural terms
        if (empty($prices)) {
            $allText = $xp->query('//text()[contains(.,"$") or contains(.,"USD") or contains(.,"ZWL")]');
            foreach ($allText as $textNode) {
                $text = trim($textNode->textContent);
                $agriculturalTerms = ['tractor', 'plow', 'seeder', 'harvester', 'irrigation', 'fertilizer', 'seed', 'crop', 'farm', 'agriculture'];
                
                if (preg_match('/([A-Za-z\s]+).*?(\$[\d,]+\.?\d*|USD[\d,]+\.?\d*|ZWL[\d,]+\.?\d*)/', $text, $matches)) {
                    $name = trim($matches[1]);
                    $price = $this->parsePrice($matches[2]);
                    
                    // Check if it's agricultural related
                    $isAgricultural = false;
                    foreach ($agriculturalTerms as $term) {
                        if (stripos($name, $term) !== false) {
                            $isAgricultural = true;
                            break;
                        }
                    }
                    
                    if ($name !== '' && $price !== null && strlen($name) > 3 && $isAgricultural) {
                        $prices[] = [
                            'item' => $name,
                            'quantity' => 'per unit',
                            'price_usd' => $price,
                            'price_zig' => null,
                            'market' => $config['market'],
                            'province' => $config['province'],
                            'category' => $config['category'],
                            'scraped_at' => now()->toISOString(),
                            'price_date' => $date,
                        ];
                    }
                }
            }
        }

        return $prices;
    }

    private function storeCategoryData(string $category, array $prices): void
    {
        $priceDate = now()->toDateString();
        
        // Store category-specific files
        $filename = "market_prices/{$category}_{$priceDate}.json";
        Storage::disk('local')->put($filename, json_encode($prices, JSON_PRETTY_PRINT));
        
        // Store latest for each category
        Storage::disk('local')->put("market_prices/latest_{$category}.json", json_encode($prices, JSON_PRETTY_PRINT));
        
        // Store combined latest
        $allLatest = [];
        foreach (array_keys($this->sources) as $cat) {
            $content = Storage::disk('local')->get("market_prices/latest_{$cat}.json");
            if ($content) {
                $allLatest = array_merge($allLatest, json_decode($content, true));
            }
        }
        Storage::disk('local')->put('market_prices/latest_all_categories.json', json_encode($allLatest, JSON_PRETTY_PRINT));
    }

    private function parsePrice(string $value): ?float
    {
        $value = str_replace(['US$', 'ZIG', 'ZWL', 'RTGS', ' ', ',', '$'], '', $value);
        $value = preg_replace('/[^0-9.]/', '', $value);
        return is_numeric($value) ? (float)$value : null;
    }
}
