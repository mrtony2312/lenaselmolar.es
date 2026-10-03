<?php

namespace App\Domain\Merchant\Support;

class BrandResolver
{
    public static function resolve(array $product): ?string
    {
        $stored = trim((string) ($product['brand'] ?? ''));

        if ($stored !== '') {
            return $stored;
        }

        $title = (string) ($product['title'] ?? '');
        $needles = config('merchant.brands', []);

        uksort($needles, fn ($a, $b) => mb_strlen($b) <=> mb_strlen($a));

        foreach ($needles as $needle => $brand) {
            if ($needle !== '' && mb_stripos($title, $needle) !== false) {
                return $brand;
            }
        }

        $fallback = trim((string) config('merchant.default_brand', ''));

        return $fallback !== '' ? $fallback : null;
    }
}
