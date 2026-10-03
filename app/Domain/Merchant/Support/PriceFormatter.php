<?php

namespace App\Domain\Merchant\Support;

use App\Support\Money;

class PriceFormatter
{
    /**
     * Google Merchant price: "29.99 EUR" (dot decimal, ISO currency).
     */
    public static function google(float $amount, ?string $currency = null): string
    {
        $currency = $currency ?: (string) config('merchant.currency', 'EUR');

        return number_format($amount, 2, '.', '').' '.$currency;
    }

    public static function amount(mixed $raw): float
    {
        return round(Money::toFloat($raw), 2);
    }
}
