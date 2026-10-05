<?php

namespace App\Support;

/**
 * Derives and validates the Google Merchant unit-pricing attributes
 * (unit_pricing_measure / unit_pricing_base_measure) from a product title,
 * and computes the "precio por unidad de medida" (Real Decreto 3423/2000)
 * shared by the feed and the storefront.
 *
 * The `products.unit_measure_value` / `unit_measure_unit` columns are the
 * source of truth. This class is used both to backfill those columns in
 * bulk (parseFromTitle) and, at render time, to turn them into the
 * feed/display strings (measureString/basePricingString/unitPrice).
 */
class UnitPricing
{
    /**
     * Products sold per unit (piece), never by weight/volume, regardless of
     * what numbers appear in their title (a boiler's tank capacity, a
     * stove's kW rating, a pipe's diameter are not the product's quantity).
     */
    private const EXCLUDED_KEYWORDS = [
        'estufa', 'caldera', 'chimenea', 'quemador',
        'accesorio', 'recambio', 'tubo', 'limpieza',
        'barbacoa', 'parrilla',
    ];

    private const EXCLUDED_CATEGORIES = [
        'estufas-de-pellets', 'calderas-de-lena', 'cocinas-de-lena',
    ];

    /**
     * Google Merchant unit codes accepted in unit_pricing_measure /
     * unit_pricing_base_measure. https://support.google.com/merchants/answer/6212410
     */
    private const VALID_UNIT_CODES = [
        // weight
        'mg', 'g', 'kg', 'oz', 'lb',
        // volume
        'ml', 'cl', 'l', 'cbm', 'floz', 'pt', 'qt', 'gal',
        // length
        'in', 'ft', 'yd', 'cm', 'm',
        // area
        'sqft', 'sqm',
        // count
        'ct',
    ];

    private const VALID_BASE_INTEGERS = [1, 2, 4, 8, 10, 100];

    /**
     * Plausible €/unit ranges for the fuels sold here (wood, pellets,
     * densified wood). Outside them, the title quantity and the price
     * cannot both be right.
     */
    public const PLAUSIBLE_RANGES = [
        'kg' => [0.08, 2.0],
        'cbm' => [20.0, 1000.0],
    ];

    public static function isPlausible(float $price, ?float $value, ?string $unit): bool
    {
        if ($value === null || $value <= 0 || $unit === null || ! isset(self::PLAUSIBLE_RANGES[$unit])) {
            return true;
        }

        [$low, $high] = self::PLAUSIBLE_RANGES[$unit];
        $perUnit = $price / $value;

        return $perUnit >= $low && $perUnit <= $high;
    }

    private const VALID_BASE_COMBOS = ['75cl', '750ml', '50kg', '1000kg'];

    /**
     * Try to derive {value, unit} from a product title. Returns null when
     * the product is excluded (sold per piece) or no quantity pattern
     * matches. $value is always expressed in the base unit's natural scale
     * (weights in kg, volumes in cbm/l) so that a 15kg bag, a 990kg pallet
     * and a 1000kg big bag are all genuinely comparable once priced per
     * their common base.
     */
    public static function parseFromTitle(string $title, ?string $category = null): ?array
    {
        $normalized = self::normalize($title);

        if (self::isExcluded($normalized, $category)) {
            return null;
        }

        // 1. Composed pallet: "palet 66 sacos de 15 kg" -> 990 kg, or
        // "70 sacos de 15 kg". Must run before the plain-weight rule, or
        // "15 kg" alone would be captured and the unit price would be
        // published dozens of times too high. Not anchored on the word
        // "palé"/"palet" itself: some titles list the sack count without
        // it ("77 sacos de 15 kg"), and requiring it also risks matching
        // across an unrelated "palé" mentioned much earlier in the title.
        //
        // "N sacos (M kg)" is a different phrasing: M there is already the
        // pallet's total weight, not a per-sack weight to multiply by N
        // ("70 sacos (1.050 kg)" means 1050 kg total, not 70×1050 kg).
        if (preg_match('/(\d+)\s*sac\w*\s*\(\s*(\d+(?:[.,]\d+)?)\s*(kg|g)\s*\)/u', $normalized, $m)) {
            $total = self::parseNumber($m[2]);
            $total = $m[3] === 'g' ? $total / 1000 : $total;

            return ['value' => $total, 'unit' => 'kg'];
        }

        if (preg_match('/(\d+)\s*sac\w*\s*de\s*(\d+(?:[.,]\d+)?)\s*(kg|g)\b/u', $normalized, $m)) {
            $count = (int) $m[1];
            $each = self::parseNumber($m[2]);
            $unit = $m[3];
            $totalKg = $unit === 'g' ? ($count * $each) / 1000 : $count * $each;

            return ['value' => $totalKg, 'unit' => 'kg'];
        }

        // 2. Tonnes: "1 tonelada", "2 t" -> kg.
        if (preg_match('/(\d+(?:[.,]\d+)?)\s*(?:toneladas?|\bt\b)/u', $normalized, $m)) {
            return ['value' => self::parseNumber($m[1]) * 1000, 'unit' => 'kg'];
        }

        // 3. Volume: m3, m³, metros cúbicos -> cbm. "Estéreos" (stacked
        // volume) are NOT converted: the catalog's own titles show they are
        // not equal to m³ ("1,3 m³ – 1,25 estéreos"), and "stere" is not a
        // Google unit. A title with only estéreos gets no measure (human
        // input required) rather than an invented conversion.
        if (preg_match('/(\d+(?:[.,]\d+)?)\s*(?:m3|m³|metros?\s*cubicos?)(?!\w)/u', $normalized, $m)) {
            return ['value' => self::parseNumber($m[1]), 'unit' => 'cbm'];
        }

        if (preg_match('/estereos?\b/u', $normalized)) {
            return null;
        }

        // 4. Plain weight: "15 kg", "500 g" -> normalized to kg.
        if (preg_match('/(\d+(?:[.,]\d+)?)\s*(kg|g)\b/u', $normalized, $m)) {
            $value = self::parseNumber($m[1]);
            $value = $m[2] === 'g' ? $value / 1000 : $value;

            return ['value' => $value, 'unit' => 'kg'];
        }

        // 5. Litres.
        if (preg_match('/(\d+(?:[.,]\d+)?)\s*(?:litros?|\bl\b)\b/u', $normalized, $m)) {
            return ['value' => self::parseNumber($m[1]), 'unit' => 'l'];
        }

        return null;
    }

    private static function isExcluded(string $normalizedTitle, ?string $category): bool
    {
        if ($category && in_array($category, self::EXCLUDED_CATEGORIES, true)) {
            return true;
        }

        foreach (self::EXCLUDED_KEYWORDS as $keyword) {
            if (str_contains($normalizedTitle, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Strip accents and lowercase, so matching is accent/case-insensitive
     * ("Palé" / "palet" / "Metros Cúbicos" all normalize the same way).
     */
    public static function normalize(string $text): string
    {
        $text = mb_strtolower($text);
        $map = [
            'á' => 'a', 'à' => 'a', 'ä' => 'a', 'â' => 'a',
            'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e',
            'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'î' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'ô' => 'o',
            'ú' => 'u', 'ù' => 'u', 'ü' => 'u', 'û' => 'u',
            'ñ' => 'n', 'ç' => 'c',
        ];

        return strtr($text, $map);
    }

    /**
     * Spanish numeric formats: "1.050" (thousands dot) -> 1050,
     * "2,5" (decimal comma) -> 2.5.
     */
    public static function parseNumber(string $raw): float
    {
        $raw = trim($raw);

        if (preg_match('/^\d{1,3}(\.\d{3})+$/', $raw)) {
            return (float) str_replace('.', '', $raw);
        }

        if (preg_match('/^\d+,\d+$/', $raw)) {
            return (float) str_replace(',', '.', $raw);
        }

        return (float) $raw;
    }

    /**
     * Google-formatted measure string, e.g. "990kg", "2.5cbm". Returns null
     * (never a guessed/rounded value) when the unit isn't in the closed
     * code list — an invalid attribute is omitted, not "fixed".
     */
    public static function measureString(?float $value, ?string $unit): ?string
    {
        if ($value === null || $value <= 0 || $unit === null || ! in_array($unit, self::VALID_UNIT_CODES, true)) {
            return null;
        }

        // Google's own unit_pricing_measure examples ("9 l", "125 ml",
        // "2.38 sqm") put a space between the value and the unit — unlike
        // unit_pricing_base_measure, which is unspaced ("1kg", "750ml").
        // https://support.google.com/merchants/answer/6324455
        return self::formatNumber($value).' '.$unit;
    }

    /**
     * Base measure string for the unit actually stored ("1kg", "1cbm",
     * "1l" per the Phase 1 convention). Unspaced, per Google's
     * unit_pricing_base_measure format. Validated against Google's closed
     * list of accepted base measures before being returned.
     */
    public static function baseMeasureString(?string $unit): ?string
    {
        if ($unit === null) {
            return null;
        }

        $candidate = '1'.$unit;

        if (self::isValidBaseMeasure($candidate)) {
            return $candidate;
        }

        return null;
    }

    private static function isValidBaseMeasure(string $value): bool
    {
        if (in_array($value, self::VALID_BASE_COMBOS, true)) {
            return true;
        }

        if (preg_match('/^(\d+)([a-z]+)$/', $value, $m)) {
            return in_array((int) $m[1], self::VALID_BASE_INTEGERS, true)
                && in_array($m[2], self::VALID_UNIT_CODES, true);
        }

        return false;
    }

    /**
     * Price per base unit, e.g. 0.37 for a 40.50 EUR / 110 kg product with
     * base 1kg. Null when there's nothing to divide by.
     */
    public static function pricePerBaseUnit(float $price, ?float $value): ?float
    {
        if ($value === null || $value <= 0) {
            return null;
        }

        return $price / $value;
    }

    /**
     * Spanish-formatted display string for the storefront, e.g. "0,37 €/kg".
     * Reuses pricePerBaseUnit so the site and the feed can never diverge.
     */
    public static function displayString(float $price, ?float $value, ?string $unit): ?string
    {
        $perUnit = self::pricePerBaseUnit($price, $value);

        if ($perUnit === null || $unit === null) {
            return null;
        }

        return number_format($perUnit, 2, ',', '.').' €/'.$unit;
    }

    private static function formatNumber(float $value): string
    {
        // Whole numbers print without decimals ("990kg"); otherwise trim
        // trailing zeros but keep a dot decimal separator (Google's feed
        // format is not locale-specific).
        if (floor($value) === $value) {
            return (string) (int) $value;
        }

        return rtrim(rtrim(number_format($value, 3, '.', ''), '0'), '.');
    }
}
