<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

/**
 * Manual entry point for eprel_code (no wp-admin/WooCommerce product tab
 * exists in this Laravel catalog, so this is the equivalent "field"). Only
 * meaningful for estufas-de-pellets / calderas-de-lena — the feed's
 * appendCertification() only emits the g:certification block for those
 * categories anyway, so setting it elsewhere is harmless but does nothing.
 */
class SetEprelCode extends Command
{
    protected $signature = 'products:set-eprel {id : Product id} {code : EPREL registration number (digits only, from the end of the model\'s EPREL URL)}';

    protected $description = 'Manually set the eprel_code for one product (estufas/calderas)';

    private const EPREL_CATEGORIES = ['estufas-de-pellets', 'calderas-de-lena'];

    public function handle(): int
    {
        $product = Product::find((int) $this->argument('id'));

        if (! $product) {
            $this->error('Produit introuvable: #'.$this->argument('id'));

            return self::FAILURE;
        }

        $code = trim((string) $this->argument('code'));

        if (! preg_match('/^\d+$/', $code)) {
            $this->error('Le code EPREL doit être numérique (uniquement des chiffres).');

            return self::FAILURE;
        }

        if (! in_array($product->category, self::EPREL_CATEGORIES, true)) {
            $this->warn(sprintf(
                'Attention : la catégorie "%s" n\'est pas dans la liste des catégories certifiées (%s). Le code sera enregistré mais le flux ne l\'émettra pas.',
                $product->category,
                implode(', ', self::EPREL_CATEGORIES)
            ));
        }

        $product->eprel_code = $code;
        $product->save();

        $this->info(sprintf('#%d %s -> eprel_code = %s', $product->id, $product->title, $code));

        return self::SUCCESS;
    }
}
