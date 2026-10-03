<?php

namespace App\Console\Commands;

use App\Repositories\LojaProduct;
use Illuminate\Console\Command;

/**
 * Audits every image_link / additional_image_link the feed would emit:
 * reads the real dimensions of the file the feed serves (after
 * FeedController's largestVariant() substitution) and flags anything under
 * Google's 500x500 minimum (enforced from 2027-01-31).
 */
class AuditFeedImages extends Command
{
    protected $signature = 'feed:audit-images {--out=storage/app/feed-image-audit.csv : Path of the CSV report}';

    protected $description = 'Check real dimensions of every image the Google Merchant feed references';

    private const MIN_DIMENSION = 500;

    public function handle(): int
    {
        $products = LojaProduct::query()->get();
        $rows = [];

        foreach ($products as $product) {
            $images = array_values(array_filter($product['images'] ?? [], fn ($i) => ! empty($i)));
            $main = $images[0] ?? ($product['hover_image'] ?? null);

            if (empty($main)) {
                continue;
            }

            $all = array_unique(array_merge([$main], $images));

            foreach ($all as $image) {
                $served = $this->largestVariant($image);
                $path = public_path($served);

                if (! is_file($path)) {
                    $rows[] = [$product['id'], $product['title'], $served, '', '', 'FICHIER_INTROUVABLE'];

                    continue;
                }

                $size = @getimagesize($path);

                if (! $size) {
                    $rows[] = [$product['id'], $product['title'], $served, '', '', 'ILLISIBLE'];

                    continue;
                }

                [$width, $height] = $size;
                $ok = $width >= self::MIN_DIMENSION && $height >= self::MIN_DIMENSION;

                $rows[] = [$product['id'], $product['title'], $served, $width, $height, $ok ? 'oui' : 'non'];
            }
        }

        $outPath = base_path($this->option('out'));
        $handle = fopen($outPath, 'w');
        fputcsv($handle, ['id_produit', 'titre', 'url', 'largeur', 'hauteur', 'conforme']);
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);

        $nonConformes = array_filter($rows, fn ($r) => $r[5] === 'non');
        $this->info(sprintf('%d images vérifiées, %d non conformes (< %dx%d px). Rapport : %s',
            count($rows), count($nonConformes), self::MIN_DIMENSION, self::MIN_DIMENSION, $outPath));

        foreach ($nonConformes as $r) {
            $this->line(sprintf('  #%s %s — %sx%s — %s', $r[0], $r[1], $r[3], $r[4], $r[2]));
        }

        return self::SUCCESS;
    }

    /**
     * Mirrors FeedController::largestVariant(): the feed prefers the
     * un-suffixed original over a "-WxH" thumbnail when the original is
     * genuinely larger and present on disk.
     */
    private function largestVariant(string $path): string
    {
        if (! preg_match('/^(.*)-(\d+)x(\d+)(\.[a-zA-Z]+)$/', $path, $m)) {
            return $path;
        }

        $original = $m[1].$m[4];
        $originalFile = public_path($original);

        if (! is_file($originalFile)) {
            return $path;
        }

        $size = @getimagesize($originalFile);

        if ($size && $size[0] >= (int) $m[2] && $size[1] >= (int) $m[3]) {
            return $original;
        }

        return $path;
    }
}
