<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencySetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'base_currency',
        'zwg_to_usd_rate',
        'rate_updated_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'zwg_to_usd_rate' => 'decimal:4',
            'rate_updated_at' => 'datetime',
        ];
    }

    /**
     * Get the current currency settings.
     */
    public static function current(): ?self
    {
        return self::first();
    }

    /**
     * Convert price from one currency to another.
     */
    public static function convert(float $amount, string $from, string $to): float
    {
        $settings = self::current();

        if (! $settings || $from === $to) {
            return $amount;
        }

        // Convert to USD first (base currency)
        $usdAmount = $from === 'USD' ? $amount : $amount / $settings->zwg_to_usd_rate;

        // Convert to target currency
        return $to === 'USD' ? $usdAmount : $usdAmount * $settings->zwg_to_usd_rate;
    }

    /**
     * Get formatted exchange rate.
     */
    public function getFormattedRateAttribute(): string
    {
        return 'ZWG$'.number_format($this->zwg_to_usd_rate, 4).' = $1.00 USD';
    }
}





