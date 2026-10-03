<?php

namespace App\Domain\Merchant\Support;

class TitleBuilder
{
    private const MAX_LENGTH = 150;

    private const BANNED = [
        'SOLDE', 'SOLDESS', 'PROMO', 'PROMOTION', 'OFERTÓN', 'LIQUIDACION',
        'LIQUIDACIÓN', '!!!', '!!!', '🔥', '⭐', '💥', '💯',
    ];

    /**
     * Brand + distinctive title, ≤150 chars, no promo spam / ALL CAPS.
     */
    public static function build(string $title, ?string $brand = null, ?string $color = null): string
    {
        $clean = self::plain($title);

        foreach (self::BANNED as $word) {
            $clean = str_ireplace($word, '', $clean);
        }

        $clean = trim(preg_replace('/\s+/', ' ', $clean) ?? $clean);

        if ($clean !== '' && $clean === mb_strtoupper($clean) && preg_match('/[A-ZÁÉÍÓÚÑ]{8,}/u', $clean)) {
            $clean = mb_convert_case(mb_strtolower($clean), MB_CASE_TITLE, 'UTF-8');
        }

        if ($brand && $brand !== '' && ! str_contains(mb_strtolower($clean), mb_strtolower($brand))) {
            $clean = $brand.' '.$clean;
        }

        if ($color && $color !== '' && ! str_contains(mb_strtolower($clean), mb_strtolower($color))) {
            $clean = $clean.' '.$color;
        }

        $clean = trim(preg_replace('/\s+/', ' ', $clean) ?? $clean);

        if (mb_strlen($clean) > self::MAX_LENGTH) {
            $clean = rtrim(mb_substr($clean, 0, self::MAX_LENGTH - 1)).'…';
        }

        return $clean;
    }

    public static function description(string $html): string
    {
        // Drop script/style blocks with their content before stripping tags.
        $html = preg_replace('#<(script|style|noscript)\b[^>]*>.*?</\1>#is', ' ', $html) ?? $html;
        $html = preg_replace('#<br\s*/?>|</p>|</li>#i', ' ', $html) ?? $html;
        $text = self::clean(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $text = preg_replace('/https?:\/\/\S+/i', '', $text) ?? $text;
        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? $text);

        if (mb_strlen($text) > 5000) {
            $text = rtrim(mb_substr($text, 0, 4999)).'…';
        }

        return $text;
    }

    /**
     * Removes characters that are invalid in XML 1.0 or invisible
     * (zero-width spaces, BOM, bidi controls) and could alter how a title
     * is matched or displayed.
     */
    public static function clean(string $text): string
    {
        $text = preg_replace('/[\x{0000}-\x{0008}\x{000B}\x{000C}\x{000E}-\x{001F}\x{007F}-\x{009F}\x{200B}-\x{200F}\x{202A}-\x{202E}\x{2060}-\x{2064}\x{FEFF}\x{FFFE}\x{FFFF}]/u', '', $text) ?? '';
        $text = str_replace("\u{00A0}", ' ', $text);

        return $text;
    }

    private static function plain(string $text): string
    {
        return trim(self::clean(html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    }
}
