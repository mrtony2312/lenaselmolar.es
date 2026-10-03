<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Explicit, human-verified identifiers and a controlled exclusion switch.
 *
 * - gtin / mpn: filled only with identifiers confirmed from the manufacturer
 *   or the packaging. Nothing is copied here automatically: `ref` keeps the
 *   historical supplier/Woo code and is only used as a fallback that
 *   merchant:validate flags for confirmation.
 * - is_active: false = withdrawn from sale (storefront 404, cart refused,
 *   feed excluded).
 * - merchant_excluded + reason: kept on sale on the site but withheld from
 *   Google on purpose; the reason is logged and shown by merchant:validate.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('gtin', 14)->nullable()->after('ref');
            $table->string('mpn', 70)->nullable()->after('gtin');
            $table->boolean('is_active')->default(true)->after('in_stock');
            $table->boolean('merchant_excluded')->default(false)->after('is_active');
            $table->string('merchant_exclusion_reason')->nullable()->after('merchant_excluded');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['gtin', 'mpn', 'is_active', 'merchant_excluded', 'merchant_exclusion_reason']);
        });
    }
};
