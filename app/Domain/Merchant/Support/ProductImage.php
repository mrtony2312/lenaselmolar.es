<?php

namespace App\Domain\Merchant\Support;

class ProductImage
{
    public const MIN_DIMENSION = 500;

    /**
     * First gallery image whose largest on-disk variant is ≥ 500×500,
     * falling back to the first path (Google still needs an image_link).
     */
    public static function main(array $product): ?string
    {
        $images = self::gallery($product);

        foreach ($images as $image) {
            $resolved = self::largestVariant($image);
            if (self::meetsMinDimensions($resolved)) {
                return $resolved;
            }
        }

        $fallback = $images[0] ?? null;

        return $fallback ? self::largestVariant($fallback) : null;
    }

    /**
     * Extra images (max 10) that already meet the minimum size.
     */
    public static function additional(array $product, ?string $mainResolved): array
    {
        $extra = [];
        $mainResolved = $mainResolved ?? '';

        foreach (self::gallery($product) as $image) {
            $resolved = self::largestVariant($image);

            if ($resolved === $mainResolved || in_array($resolved, $extra, true)) {
                continue;
            }

            if (! self::meetsMinDimensions($resolved)) {
                continue;
            }

            $extra[] = $resolved;

            if (count($extra) >= 10) {
                break;
            }
        }

        return $extra;
    }

    public static function publicUrl(string $relativePath): string
    {
        return asset(ltrim($relativePath, '/'));
    }

    public static function largestVariant(string $path): string
    {
        $path = ltrim($path, '/');

        if (! preg_match('/^(.*)-(\d+)x(\d+)(\.[a-zA-Z]+)$/', $path, $m)) {
            return $path;
        }

        $original = $m[1].$m[4];
        $size = self::dimensions($original);

        if ($size && $size[0] >= (int) $m[2] && $size[1] >= (int) $m[3]) {
            return $original;
        }

        return $path;
    }

    public static function meetsMinDimensions(string $servedPath): bool
    {
        $size = self::dimensions($servedPath);

        return $size && $size[0] >= self::MIN_DIMENSION && $size[1] >= self::MIN_DIMENSION;
    }

    /**
     * Real pixel size of a file under public/, memoised per process
     * (the feed, the validator and the PDP ask for the same files).
     *
     * @return array{0: int, 1: int, 2: string}|null [width, height, mime]
     */
    public static function dimensions(string $servedPath): ?array
    {
        static $cache = [];

        $file = public_path(ltrim($servedPath, '/'));

        if (array_key_exists($file, $cache)) {
            return $cache[$file];
        }

        if (! is_file($file)) {
            return $cache[$file] = null;
        }

        $size = @getimagesize($file);

        return $cache[$file] = $size ? [(int) $size[0], (int) $size[1], (string) ($size['mime'] ?? '')] : null;
    }

    /**
     * Every gallery path as served on the product page.
     *
     * @return list<string>
     */
    public static function galleryPaths(array $product): array
    {
        return self::gallery($product);
    }

    private static function gallery(array $product): array
    {
        $images = array_values(array_filter($product['images'] ?? [], fn ($i) => is_string($i) && $i !== ''));

        if ($images === [] && ! empty($product['hover_image'])) {
            $images[] = $product['hover_image'];
        }

        return $images;
    }
}
