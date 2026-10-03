<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('email');
            $table->string('status')->default('pending_confirmation');
            $table->decimal('products_total', 12, 2)->default(0);
            $table->json('payload');
            $table->timestamps();
        });

        if (Schema::hasTable('products')) {
            DB::table('products')->where('id', 5802)->update([
                'title' => 'Pellet Valboval – palé de 65 sacos de 15 kg (ref. 53745802)',
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
