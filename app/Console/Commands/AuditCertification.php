<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

/**
 * Reports, for estufas-de-pellets and calderas-de-lena, how many products
 * have a usable GTIN/MPN so Google could infer the energy certification
 * automatically when no eprel_code is set, and how many are still missing
 * an eprel_code entirely.
 *
 * The catalog currently has no `gtin`/`mpn` columns at all — only `ref`,
 * which looks like an internal SKU (see the heuristic below), not a
 * manufacturer part number. This command reports that gap; it does not
 * invent identifiers.
 */
class AuditCertification extends Command
{
    protected $signature = 'products:audit-certification';

    protected $description = 'Audit GTIN/MPN/EPREL data quality for estufas and calderas';

    private const CATEGORIES = ['estufas-de-pellets', 'calderas-de-lena'];

    public function handle(): int
    {
        $products = Product::query()->whereIn('category', self::CATEGORIES)->get();

        $withGtin = 0; // no `gtin` column exists in the schema today
        $withInternalLookingMpn = 0;
        $missingEprel = [];

        $plausibleGtin = 0;

        foreach ($products as $product) {
            if ($this->looksLikeInternalReference($product->ref, (int) $product->id)) {
                $withInternalLookingMpn++;
            } elseif ($this->looksLikeGtin($product->ref)) {
                $plausibleGtin++;
            }

            if (empty($product->eprel_code)) {
                $missingEprel[] = sprintf('#%d %s (ref: %s)', $product->id, $product->title, $product->ref ?? '—');
            }
        }

        $this->info(sprintf('Produits estufas/calderas examinés : %d', $products->count()));
        $this->line(sprintf('- Avec GTIN valide : %d (aucune colonne `gtin` en base actuellement)', $withGtin));
        $this->line(sprintf(
            '- Avec un `ref` qui ressemble à une référence interne (contient l\'id catalogue) plutôt qu\'à une référence fabricant : %d/%d',
            $withInternalLookingMpn,
            $products->count()
        ));
        $this->line(sprintf(
            '- Avec un `ref` qui ressemble déjà à un GTIN/EAN plausible (8/12/13/14 chiffres, hors id catalogue) : %d/%d — utilisable comme `gtin` une fois vérifié, pas comme `mpn`',
            $plausibleGtin,
            $products->count()
        ));
        $this->line(sprintf('- Sans eprel_code renseigné : %d', count($missingEprel)));

        if ($missingEprel) {
            $this->newLine();
            $this->line('Produits en attente d\'un code EPREL :');
            foreach ($missingEprel as $line) {
                $this->line('  '.$line);
            }
        }

        return self::SUCCESS;
    }

    /**
     * Heuristic only, since there's no dedicated mpn column to compare
     * against: a numeric string that embeds the product's own catalog id
     * looks like an internal SKU (e.g. id 5522 -> ref "53745522"), not a
     * manufacturer part number.
     */
    private function looksLikeInternalReference(?string $ref, int $productId): bool
    {
        if (empty($ref) || ! preg_match('/^\d+$/', $ref)) {
            return false;
        }

        return str_contains($ref, (string) $productId);
    }

    /**
     * A purely numeric reference of GTIN/EAN length (8, 12, 13 or 14
     * digits) that does NOT embed the catalog id is plausibly an actual
     * barcode stored under the wrong field name, not a manufacturer part
     * number — worth checking manually rather than assuming either way.
     */
    private function looksLikeGtin(?string $ref): bool
    {
        if (empty($ref) || ! preg_match('/^\d+$/', $ref)) {
            return false;
        }

        return in_array(strlen($ref), [8, 12, 13, 14], true);
    }
}
