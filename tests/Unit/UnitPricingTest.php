<?php

namespace Tests\Unit;

use App\Support\UnitPricing;
use PHPUnit\Framework\TestCase;

class UnitPricingTest extends TestCase
{
    /**
     * @dataProvider titleProvider
     */
    public function test_parse_from_title(string $title, ?string $category, ?array $expected): void
    {
        $this->assertSame($expected, UnitPricing::parseFromTitle($title, $category));
    }

    public static function titleProvider(): array
    {
        return [
            'saco simple' => ['Saco de pellets 15 kg', 'pellets-de-madera', ['value' => 15.0, 'unit' => 'kg']],
            'palet compuesto' => ['Palet 66 sacos de 15 kg', 'pellets-de-madera', ['value' => 990.0, 'unit' => 'kg']],
            'palet formato ES miles' => ['Palet de pellets 1.050 kg', 'pellets-de-madera', ['value' => 1050.0, 'unit' => 'kg']],
            'big bag' => ['Big Bag pellets 1000 kg', 'a-granel', ['value' => 1000.0, 'unit' => 'kg']],
            'tonelada' => ['Pellets a granel 1 tonelada', 'a-granel', ['value' => 1000.0, 'unit' => 'kg']],
            'volumen m3' => ['Leña de encina seca 1 m3', 'lena', ['value' => 1.0, 'unit' => 'cbm']],
            'volumen metros cubicos decimal ES' => ['Leña de olivo 2,5 metros cúbicos', 'lena', ['value' => 2.5, 'unit' => 'cbm']],
            'peso simple briquetas' => ['Briquetas caja 10 kg', 'madera-densificada', ['value' => 10.0, 'unit' => 'kg']],
            'm3 junto a estereos usa m3' => ['Leña – 40 cm – 1,3 m³ – 1,25 estéreos', 'lena', ['value' => 1.3, 'unit' => 'cbm']],
            'solo estereos no se convierte' => ['Palé de leña – 25 cm – 3 estéreos', 'lena', null],
            'estufa excluida' => ['Estufa de pellets 10 kW', 'estufas-de-pellets', null],
            'caldera con deposito excluida' => ['Caldera de pellets 24 kW con depósito 40 kg', 'calderas-de-lena', null],
            'tubo accesorio excluido' => ['Tubo estufa 80 mm inox 1 m', 'estufas-de-pellets', null],
        ];
    }

    public function test_measure_string_formats_whole_and_decimal_values(): void
    {
        // Google's format has a space between value and unit for
        // unit_pricing_measure (unlike the unspaced base measure).
        $this->assertSame('990 kg', UnitPricing::measureString(990.0, 'kg'));
        $this->assertSame('2.5 cbm', UnitPricing::measureString(2.5, 'cbm'));
    }

    public function test_measure_string_omits_invalid_unit_instead_of_correcting_it(): void
    {
        $this->assertNull(UnitPricing::measureString(10.0, 'm3'));
        $this->assertNull(UnitPricing::measureString(0, 'kg'));
        $this->assertNull(UnitPricing::measureString(null, 'kg'));
    }

    public function test_base_measure_string_accepts_only_closed_list_values(): void
    {
        $this->assertSame('1kg', UnitPricing::baseMeasureString('kg'));
        $this->assertSame('1cbm', UnitPricing::baseMeasureString('cbm'));
        $this->assertSame('1l', UnitPricing::baseMeasureString('l'));
        $this->assertNull(UnitPricing::baseMeasureString(null));
    }

    public function test_display_string_matches_price_divided_by_value(): void
    {
        // 40.50 EUR for a 110 kg product -> 0.3681... -> "0,37 €/kg"
        $this->assertSame('0,37 €/kg', UnitPricing::displayString(40.50, 110.0, 'kg'));
    }

    public function test_display_string_is_null_without_a_measure(): void
    {
        $this->assertNull(UnitPricing::displayString(40.50, null, 'kg'));
    }
}
