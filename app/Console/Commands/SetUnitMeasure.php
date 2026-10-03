<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Support\UnitPricing;
use Illuminate\Console\Command;

/**
 * Manual override for a single product's unit_measure_value/unit — the
 * source of truth for unit_pricing_measure/unit_pricing_base_measure in
 * the feed (see FeedController::appendUnitPricing). Use this whenever the
 * title doesn't state a per-bag/per-pallet weight products:backfill-unit-measure
 * can parse (e.g. "Palé de pellets Proxima Star" gives no weight at all).
 */
class SetUnitMeasure extends Command
{
    protected $signature = 'products:set-unit-measure {id : Product id} {value : Quantity, in the unit\'s natural scale (e.g. 15, 990, 1)} {unit : kg|g|l|cbm|... (Google unit code)}';

    protected $description = 'Manually set unit_measure_value/unit_measure_unit for one product';

    public function handle(): int
    {
        $product = Product::find((int) $this->argument('id'));

        if (! $product) {
            $this->error('Produit introuvable: #'.$this->argument('id'));

            return self::FAILURE;
        }

        $value = (float) $this->argument('value');
        $unit = $this->argument('unit');

        if (UnitPricing::measureString($value, $unit) === null) {
            $this->error(sprintf('Unité "%s" non reconnue par Google (liste fermée). Rien n\'a été enregistré.', $unit));

            return self::FAILURE;
        }

        $product->unit_measure_value = $value;
        $product->unit_measure_unit = $unit;
        $product->save();

        $this->info(sprintf('#%d %s -> %s (base %s)', $product->id, $product->title, UnitPricing::measureString($value, $unit), UnitPricing::baseMeasureString($unit)));

        return self::SUCCESS;
    }
}
