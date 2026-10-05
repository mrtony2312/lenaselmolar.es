<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Fixes 55 products that were excluded from the Google Merchant feed:
 *
 *  - Title format: "N Sacos certificado Enplus 15 kg" / "N sacos x M kg"
 *    → "N sacos de M kg" so UnitPricing::parseFromTitle extracts total weight
 *    correctly and price-per-kg stays within the plausible range.
 *
 *  - Duplicate offers: products from loja_products.php that became duplicates
 *    after pellets_hogar_products.php was imported. Canonical versions from
 *    pellets_hogar are kept active; obsolete loja versions are deactivated.
 *
 *  - Image: SunFire and Ardenforest brand images removed from Pellet Badger
 *    and Excellent Pellets galleries.
 *
 *  - Typo: Naturpellet "11 palés" listed 3850 bags instead of 847 (11 × 77).
 */
return new class extends Migration
{
    public function up(): void
    {
        // ------------------------------------------------------------------
        // 1. Burpellet – rewrite titles so parser reads N×15 kg total weight
        // ------------------------------------------------------------------
        $burpellet = [
            902379 => [72,  1],
            902380 => [144, 2],
            902390 => [216, 3],
            902381 => [288, 4],
            902382 => [360, 5],
            902383 => [432, 6],
            902384 => [504, 7],
            902385 => [576, 8],
            902386 => [648, 9],
            902387 => [720, 10],
            902388 => [792, 11],
            902389 => [864, 12],
        ];
        foreach ($burpellet as $id => [$sacos, $palets]) {
            $pal = $palets === 1 ? 'palé' : 'palés';
            $palSlug = $palets === 1 ? 'pale' : 'pales';
            DB::table('products')->where('id', $id)->update([
                'title' => "Pellets Burpellet ENplus A1 – {$sacos} sacos de 15 kg ({$palets} {$pal})",
                'slug'  => "pellets-burpellet-enplus-a1-{$sacos}-sacos-15-kg-{$palets}-{$palSlug}",
            ]);
        }

        // ------------------------------------------------------------------
        // 2. Naturpellet 9024xx – fix "N sacos certificado Enplus de 15 kg"
        //    + typo: 3850 → 847 for 11-palé tier
        // ------------------------------------------------------------------
        $naturOld = [
            902391 => [616, 8],
            902392 => [693, 9],
            902393 => [770, 10],
            902394 => [847, 11], // was 3850 – typo corrected
            902395 => [308, 4],
            902490 => [385, 5],
            902491 => [462, 6],
            902492 => [539, 7],
            902496 => [45,  null],
        ];
        foreach ($naturOld as $id => [$sacos, $palets]) {
            if ($palets) {
                $pal = $palets === 1 ? 'palé' : 'palés';
                $palSlug = $palets === 1 ? 'pale' : 'pales';
                DB::table('products')->where('id', $id)->update([
                    'title' => "Pellets Naturpellet ENplus A1 – {$sacos} sacos de 15 kg ({$palets} {$pal})",
                    'slug'  => "pellets-naturpellet-enplus-a1-{$sacos}-sacos-15-kg-{$palets}-{$palSlug}",
                ]);
            } else {
                DB::table('products')->where('id', $id)->update([
                    'title' => "Pellets Naturpellet ENplus A1 – {$sacos} sacos de 15 kg",
                    'slug'  => "pellets-naturpellet-enplus-a1-{$sacos}-sacos-15-kg",
                ]);
            }
        }

        // ------------------------------------------------------------------
        // 3. Naturpellet 9025xx – fix "N sacos x 15 kg"
        // ------------------------------------------------------------------
        $naturNew = [
            902503 => [77,  1],
            902504 => [154, 2],
            902505 => [38,  null],
            902506 => [231, 3],
        ];
        foreach ($naturNew as $id => [$sacos, $palets]) {
            if ($palets) {
                $pal = $palets === 1 ? 'palé' : 'palés';
                $palSlug = $palets === 1 ? 'pale' : 'pales';
                DB::table('products')->where('id', $id)->update([
                    'title' => "Pellets Naturpellet ENplus A1 – {$sacos} sacos de 15 kg ({$palets} {$pal})",
                    'slug'  => "pellets-naturpellet-enplus-a1-{$sacos}-sacos-15-kg-{$palets}-{$palSlug}",
                ]);
            } else {
                DB::table('products')->where('id', $id)->update([
                    'title' => "Pellets Naturpellet ENplus A1 – {$sacos} sacos de 15 kg",
                    'slug'  => "pellets-naturpellet-enplus-a1-{$sacos}-sacos-15-kg",
                ]);
            }
        }

        // ------------------------------------------------------------------
        // 4. Huella Verde, Bioforestal, Asturias – fix "N sacos x M kg"
        // ------------------------------------------------------------------
        $others = [
            902507 => ['Huella Verde',   77,  1],
            902508 => ['Huella Verde',  154,  2],
            902509 => ['Huella Verde',  231,  3],
            902583 => ['Bioforestal',    42,  null],
            902584 => ['Bioforestal',    70,  null],
            902585 => ['Asturias',       72,  1],
            902586 => ['Asturias',      216,  3],
        ];
        foreach ($others as $id => [$brand, $sacos, $palets]) {
            $brandSlug = strtolower(str_replace(' ', '-', $brand));
            if ($palets) {
                $pal = $palets === 1 ? 'palé' : 'palés';
                $palSlug = $palets === 1 ? 'pale' : 'pales';
                DB::table('products')->where('id', $id)->update([
                    'title' => "Pellets {$brand} ENplus A1 – {$sacos} sacos de 15 kg ({$palets} {$pal})",
                    'slug'  => "pellets-{$brandSlug}-enplus-a1-{$sacos}-sacos-15-kg-{$palets}-{$palSlug}",
                ]);
            } else {
                DB::table('products')->where('id', $id)->update([
                    'title' => "Pellets {$brand} ENplus A1 – {$sacos} sacos de 15 kg",
                    'slug'  => "pellets-{$brandSlug}-enplus-a1-{$sacos}-sacos-15-kg",
                ]);
            }
        }

        // ------------------------------------------------------------------
        // 5. Other individual title fixes
        // ------------------------------------------------------------------
        DB::table('products')->where('id', 902499)->update([
            'title' => 'Palé de leña de encina BIOENERGY – 50 sacos de 12 kg',
            'slug'  => 'pale-de-lena-de-encina-bioenergy-50-sacos-de-12-kg',
        ]);
        DB::table('products')->where('id', 902580)->update([
            'title' => 'Pack briquetas de encina + caja Wood Balls – 140 kg (20 uds. × 7 kg)',
            'slug'  => 'pack-briquetas-encina-wood-balls-140-kg',
        ]);
        DB::table('products')->where('id', 902589)->update([
            'title' => 'Barbacoa de carbón WEBER con briquetas – para 6 a 10 comensales',
            'slug'  => 'barbacoa-carbon-weber-con-briquetas-6-a-10-comensales',
        ]);
        DB::table('products')->where('id', 902595)->update([
            'title' => 'Leña de abedul seca – 15 sacos de 8 kg – 25 cm',
            'slug'  => 'lena-de-abedul-seca-15-sacos-de-8-kg-25-cm',
        ]);
        DB::table('products')->where('id', 902684)->update([
            'title' => 'Pellets para tejones ENplus – 35 sacos de 15 kg (½ palé)',
            'slug'  => 'pellets-para-tejones-enplus-35-sacos-de-15-kg',
        ]);
        DB::table('products')->where('id', 902661)->update([
            'title' => 'Palé de pellets Proxima Star ENplus',
            'slug'  => 'pale-de-pellets-proxima-star-enplus',
        ]);
        DB::table('products')->where('id', 902774)->update([
            'title' => 'Madera densificada de coníferas – Palé de 960 kg',
            'slug'  => 'madera-densificada-coniferas-pale-de-960-kg',
        ]);

        // ------------------------------------------------------------------
        // 6. Deactivate obsolete duplicates
        // ------------------------------------------------------------------
        $deactivate = [
            5622  => 'Duplicado con 902681 (Pellets Helios 65 sacos)',
            5624  => 'Duplicado con 902661 (Proxima Star)',
            5631  => 'Duplicado con 902680 (Pellet Limouzi 66 sacos)',
            5641  => 'Duplicado con 902774 (Madera densificada 960 kg)',
            5716  => 'Título erróneo: "15 kg" incompatible con precio 366 €',
            5802  => 'Duplicado con 5633 (Pellet Valboval 65 sacos)',
            5803  => 'Duplicado con 5629 (Excellent Pellets 65 sacos)',
            5804  => 'Duplicado con 902680 (Pellet Limouzi 66 sacos)',
            31007 => 'Duplicado con 902585 (Pellets Asturias 72 sacos)',
            31009 => 'Duplicado con 902583 (Pellets Bioforestal 42 sacos)',
            902493 => 'Duplicado con 902503 (Naturpellet 77 sacos de 15 kg)',
            902494 => 'Duplicado con 902504 (Naturpellet 154 sacos de 15 kg)',
            902495 => 'Duplicado con 902506 (Naturpellet 231 sacos de 15 kg)',
        ];
        foreach ($deactivate as $id => $reason) {
            DB::table('products')->where('id', $id)->update([
                'is_active'                => false,
                'merchant_excluded'        => true,
                'merchant_exclusion_reason' => $reason,
            ]);
        }

        // ------------------------------------------------------------------
        // 7. Remove wrong-brand images
        // ------------------------------------------------------------------
        $badImages = [
            5626 => 'SunFire',
            5629 => 'Ardenforest',
        ];
        foreach ($badImages as $productId => $brandToRemove) {
            $row = DB::table('products')->where('id', $productId)->value('images');
            if (! $row) {
                continue;
            }
            $images = json_decode($row, true);
            if (! is_array($images)) {
                continue;
            }
            $cleaned = array_values(array_filter(
                $images,
                fn ($img) => stripos($img, strtolower($brandToRemove)) === false
            ));
            DB::table('products')->where('id', $productId)->update([
                'images' => json_encode($cleaned),
            ]);
        }
    }

    public function down(): void
    {
        // Intentionally left empty: title corrections are forward-only.
        // Re-running db:seed --class=CatalogSeeder restores original data.
    }
};
