<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MbareMusikaScraper;

class FetchMbareMusikaPrices extends Command
{
    protected $signature = 'market:fetch-mbare-musika';
    protected $description = 'Fetch Mbare Musika prices from ZimPriceCheck';

    public function handle(MbareMusikaScraper $scraper): int
    {
        $count = $scraper->fetchAndStore();
        $this->info("Stored {$count} Mbare Musika price records.");
        return self::SUCCESS;
    }
}
