<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Source of truth for the Google Merchant unit-pricing attributes
            // (unit_pricing_measure / unit_pricing_base_measure) and for the
            // "precio por unidad de medida" shown on the storefront (Real
            // Decreto 3423/2000). Nullable: absent means "no unit price for
            // this product" (e.g. estufas/calderas sold per unit).
            $table->decimal('unit_measure_value', 12, 3)->nullable()->after('color');
            $table->string('unit_measure_unit', 10)->nullable()->after('unit_measure_value');

            // EPREL registration number for solid-fuel estufas/calderas
            // (European energy label). Digits only, from the end of the
            // product's EPREL URL. Nullable: not every stove/boiler has one
            // registered yet.
            $table->string('eprel_code', 20)->nullable()->after('unit_measure_unit');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['unit_measure_value', 'unit_measure_unit', 'eprel_code']);
        });
    }
};
