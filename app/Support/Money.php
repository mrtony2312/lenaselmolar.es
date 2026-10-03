<?php

namespace App\Support;

/**
 * Spanish (es-ES) money formatting: thousands separator ".", decimals ",",
 * symbol after the amount — e.g. 2.699,00 €.
 *
 * Catalog prices are stored as US-formatted strings ("2,699.00", "320.00");
 * pass either those raw strings or a float.
 */
class Money
{
    public static function toFloat(string|float|int|null $raw): float
    {
        if (is_float($raw) || is_int($raw)) {
            return (float) $raw;
        }

        $v = trim((string) $raw);

        if ($v === '') {
            return 0.0;
        }

        // "2,699.00" -> drop thousands comma; also tolerate "2.699,00"
        if (preg_match('/^\d{1,3}(\.\d{3})+,\d{2}$/', $v)) {
            $v = str_replace('.', '', $v);
            $v = str_replace(',', '.', $v);
        } else {
            $v = str_replace(',', '', $v);
        }

        return (float) preg_replace('/[^0-9.\-]/', '', $v);
    }

    public static function format(string|float|int|null $raw): string
    {
        return number_format(self::toFloat($raw), 2, ',', '.');
    }

    public static function eur(string|float|int|null $raw): string
    {
        return self::format($raw).' €';
    }
}
