<?php

namespace App\Domain\Merchant\Validation;

enum Severity: string
{
    /** Blocks the product from the feed (or, account-level, fails merchant:feed-test). */
    case Critical = 'CRITICAL';

    /** Likely disapproval, limited performance or misleading display: fix soon. */
    case Important = 'IMPORTANT';

    /** Needs human verification or improves data quality. */
    case Warning = 'WARNING';

    public function rank(): int
    {
        return match ($this) {
            self::Critical => 0,
            self::Important => 1,
            self::Warning => 2,
        };
    }
}
