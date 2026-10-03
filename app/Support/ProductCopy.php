<?php

namespace App\Support;

/**
 * Removes product claims that are not documented for this merchant
 * (quality marks, measured humidity, conflicting origin statements).
 * Qualitative heating copy is left in place.
 */
class ProductCopy
{
    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    public static function apply(array $item): array
    {
        foreach (['title', 'short_description', 'description'] as $field) {
            if (array_key_exists($field, $item) && is_string($item[$field])) {
                $item[$field] = self::sanitize($item[$field]);
            }
        }

        return $item;
    }

    public static function sanitize(?string $text): string
    {
        $text = html_entity_decode((string) $text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        if (trim($text) === '') {
            return '';
        }

        $patterns = [
            '/\bcertificad[oa]s?\s+(?:DIN\s*\+?\s*plus|ENplus|EN\s*plus|NF)\b/iu',
            '/\bDIN\s*\+?\s*plus\b/iu',
            '/\bENplus®?\b/iu',
            '/\bEN\s*plus\b/iu',
            '/\bNF\s+Bois(?:\s+de\s+chauffage)?\b/iu',
            '/\b100\s*%\s+portugu[eé]s(?:es)?\b/iu',
            '/\b100\s*%\s+franc[eé]s(?:es)?\b/iu',
            '/\b(?:fabricad[oa]s?|fabricado|fabricada|se fabrica)\s+en\s+(?:Francia|Portugal|España|la regi[oó]n de [^.,;\n]+)/iu',
            '/\bhumedad\s+(?:inferior|garantizada|menor|m[aá]xima|del|de)\s+\d+(?:[.,]\d+)?\s*%/iu',
            '/\b(?:bajo\s+)?contenido\s+de\s+humedad\b/iu',
            '/\(\s*\d+(?:[.,]\d+)?\s*kWh\s*\/\s*kg\s*\)/iu',
        ];

        $clean = preg_replace($patterns, '', $text) ?? $text;
        $clean = preg_replace('/\(\s*\)/', '', $clean) ?? $clean;
        $clean = preg_replace('/\s+,/', ',', $clean) ?? $clean;
        $clean = preg_replace('/,\s*,+/', ',', $clean) ?? $clean;
        $clean = preg_replace('/\s+([.,;])/', '$1', $clean) ?? $clean;
        $clean = preg_replace('/[ \t]{2,}/', ' ', $clean) ?? $clean;
        $clean = preg_replace('/\s+y\s+([.,])/iu', '$1', $clean) ?? $clean;
        $clean = preg_replace('/\(\s*,/', '(', $clean) ?? $clean;

        return trim($clean);
    }
}
