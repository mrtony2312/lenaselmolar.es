<?php

namespace Tests\Unit;

use App\Support\ProductCopy;
use Tests\TestCase;

class ProductCopyTest extends TestCase
{
    public function test_strips_unverified_marks_humidity_and_origin(): void
    {
        $text = 'Nuestros pellets ARDENFOREST están certificados DINPlus y fabricados en la región de Champaña Ardenas. '
            .'100% franceses, bajo contenido de humedad y poder calorífico (5 kWh/kg).';

        $clean = ProductCopy::sanitize($text);

        $this->assertStringNotContainsString('DINPlus', $clean);
        $this->assertStringNotContainsString('franceses', $clean);
        $this->assertStringNotContainsString('humedad', $clean);
        $this->assertStringNotContainsString('kWh', $clean);
        $this->assertStringNotContainsString('Champaña', $clean);
        $this->assertStringContainsString('ARDENFOREST', $clean);
    }
}
