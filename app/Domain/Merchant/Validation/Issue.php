<?php

namespace App\Domain\Merchant\Validation;

/**
 * One finding. productId null = account/site-level issue.
 */
final class Issue
{
    public const PRICE = 'PRICE';
    public const AVAILABILITY = 'AVAILABILITY';
    public const IMAGE = 'IMAGE';
    public const URL = 'URL';
    public const IDENTIFIER = 'IDENTIFIER';
    public const LANGUAGE = 'LANGUAGE';
    public const SHIPPING = 'SHIPPING';
    public const CATEGORY = 'CATEGORY';
    public const VARIANT = 'VARIANT';
    public const SCHEMA = 'SCHEMA';
    public const XML = 'XML';
    public const TITLE = 'TITLE';
    public const DESCRIPTION = 'DESCRIPTION';
    public const POLICY = 'POLICY';
    public const BUSINESS = 'BUSINESS';
    public const CHECKOUT = 'CHECKOUT';
    public const CONSISTENCY = 'CONSISTENCY';

    public function __construct(
        public readonly Severity $severity,
        public readonly string $group,
        public readonly string $field,
        public readonly string $current,
        public readonly string $expected,
        public readonly string $reason,
        public readonly string $fix,
        public readonly ?int $productId = null,
        public readonly ?string $sku = null,
        public readonly ?string $name = null,
    ) {}

    public function isBlocking(): bool
    {
        return $this->severity === Severity::Critical;
    }

    /**
     * @return array<string, string|int|null>
     */
    public function toArray(): array
    {
        return [
            'severity' => $this->severity->value,
            'group' => $this->group,
            'product_id' => $this->productId,
            'sku' => $this->sku,
            'name' => $this->name,
            'field' => $this->field,
            'current' => $this->current,
            'expected' => $this->expected,
            'reason' => $this->reason,
            'fix' => $this->fix,
        ];
    }
}
