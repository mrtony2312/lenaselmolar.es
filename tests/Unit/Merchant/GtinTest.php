<?php

namespace Tests\Unit\Merchant;

use App\Domain\Merchant\Support\Gtin;
use PHPUnit\Framework\TestCase;

class GtinTest extends TestCase
{
    public function test_valid_ean13_checksum(): void
    {
        $this->assertTrue(Gtin::isValid('4006381333931'));
        $this->assertSame('4006381333931', Gtin::normalize('4006381333931'));
    }

    public function test_rejects_bad_checksum(): void
    {
        $this->assertFalse(Gtin::isValid('4006381333932'));
    }

    public function test_rejects_in_store_prefix(): void
    {
        $this->assertFalse(Gtin::isValid('2000000005157'));
    }

    public function test_rejects_empty_and_short_values(): void
    {
        $this->assertFalse(Gtin::isValid(null));
        $this->assertFalse(Gtin::isValid(''));
        $this->assertFalse(Gtin::isValid('12345'));
    }
}
