<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MultiCategoryPriceScraper;

class FetchAllMarketPrices extends Command
{
    protected $signature = 'market:fetch-all {--category= : Specific category to fetch}';
    protected $description = 'Fetch prices from all market sources';

    public function handle(MultiCategoryPriceScraper $scraper): int
    {
        if ($category = $this->option('category')) {
            $sources = $scraper->getSources();
            if (!isset($sources[$category])) {
                $this->error("Category '{$category}' not found.");
                return self::FAILURE;
            }
            
            $count = $scraper->fetchCategoryPrices($category, $sources[$category]);
            $this->info("Stored {$count} {$category} price records.");
        } else {
            $results = $scraper->fetchAllCategories();
            $total = 0;
            foreach ($results as $category => $count) {
                $this->info("Stored {$count} {$category} price records.");
                $total += $count;
            }
            $this->info("Total: {$total} price records across all categories.");
        }
        
        return self::SUCCESS;
    }
}
