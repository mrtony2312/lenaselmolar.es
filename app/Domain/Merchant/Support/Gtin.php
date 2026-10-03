<?php

namespace App\Domain\Merchant\Support;

/**
 * GS1 GTIN-8 / GTIN-12 / GTIN-13 / GTIN-14 validation (checksum + length).
 * Never invents identifiers — only accepts a value that is already valid.
 */
class Gtin
{
    public static function isValid(?string $value): bool
    {
        $digits = self::digits($value);

        if ($digits === null) {
            return false;
        }

        $length = strlen($digits);

        if (! in_array($length, [8, 12, 13, 14], true)) {
            return false;
        }

        // Restricted / in-store prefixes (GS1 20–29) are internal barcodes,
        // not manufacturer GTINs Google will accept as unique identifiers.
        if (str_starts_with($digits, '2')) {
            return false;
        }

        if (preg_match('/^0+$/', $digits)) {
            return false;
        }

        return self::checksum($digits) === (int) $digits[$length - 1];
    }

    /**
     * Normalise to the canonical digit string, or null if unusable.
     */
    public static function normalize(?string $value): ?string
    {
        return self::isValid($value) ? self::digits($value) : null;
    }

    private static function digits(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $value);

        return $digits === '' ? null : $digits;
    }

    private static function checksum(string $digits): int
    {
        $body = substr($digits, 0, -1);
        $padded = str_pad($body, 13, '0', STR_PAD_LEFT);
        $sum = 0;

        for ($i = 0; $i < 13; $i++) {
            $n = (int) $padded[$i];
            $sum += ($i % 2 === 0) ? $n * 3 : $n;
        }

        return (10 - ($sum % 10)) % 10;
    }
}
