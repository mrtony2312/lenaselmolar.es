<?php

namespace App\Domain\Merchant\Validation;

final class ValidationReport
{
    /** @var list<Issue> */
    private array $issues = [];

    /** @var array<int, string> product id => exclusion reason (inactive / manual) */
    private array $exclusions = [];

    /** @var array<int, array{sku: string, name: string}> */
    private array $products = [];

    public function addProduct(int $id, string $sku, string $name): void
    {
        $this->products[$id] = ['sku' => $sku, 'name' => $name];
    }

    public function add(Issue $issue): void
    {
        $this->issues[] = $issue;
    }

    public function exclude(int $productId, string $reason): void
    {
        $this->exclusions[$productId] = $reason;
    }

    /** @return list<Issue> */
    public function issues(): array
    {
        $issues = $this->issues;
        usort($issues, fn (Issue $a, Issue $b) => [$a->severity->rank(), $a->group, $a->productId ?? 0]
            <=> [$b->severity->rank(), $b->group, $b->productId ?? 0]);

        return $issues;
    }

    /** @return list<Issue> */
    public function accountIssues(): array
    {
        return array_values(array_filter($this->issues(), fn (Issue $i) => $i->productId === null));
    }

    public function total(): int
    {
        return count($this->products);
    }

    /**
     * Products withheld from the feed, with every reason.
     *
     * @return array<int, list<string>>
     */
    public function blocked(): array
    {
        $blocked = [];

        foreach ($this->exclusions as $id => $reason) {
            $blocked[$id][] = $reason;
        }

        foreach ($this->issues as $issue) {
            if ($issue->productId !== null && $issue->isBlocking()) {
                $blocked[$issue->productId][] = '['.$issue->group.'] '.$issue->reason;
            }
        }

        ksort($blocked);

        return $blocked;
    }

    public function isBlocked(int $productId): bool
    {
        return isset($this->blocked()[$productId]);
    }

    public function validCount(): int
    {
        return $this->total() - count($this->blocked());
    }

    public function countBySeverity(Severity $severity): int
    {
        return count(array_filter($this->issues, fn (Issue $i) => $i->severity === $severity));
    }

    /**
     * @return array<string, int>
     */
    public function countByGroup(): array
    {
        $out = [];

        foreach ($this->issues as $issue) {
            $out[$issue->group] = ($out[$issue->group] ?? 0) + 1;
        }

        ksort($out);

        return $out;
    }

    public function hasCritical(): bool
    {
        return $this->countBySeverity(Severity::Critical) > 0;
    }

    public function productName(int $id): string
    {
        return $this->products[$id]['name'] ?? '';
    }

    public function productSku(int $id): string
    {
        return $this->products[$id]['sku'] ?? '';
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'generated_at' => now()->toIso8601String(),
            'total' => $this->total(),
            'valid' => $this->validCount(),
            'blocked' => count($this->blocked()),
            'critical' => $this->countBySeverity(Severity::Critical),
            'important' => $this->countBySeverity(Severity::Important),
            'warnings' => $this->countBySeverity(Severity::Warning),
            'by_group' => $this->countByGroup(),
            'blocked_products' => array_map(fn ($reasons, $id) => [
                'product_id' => $id,
                'sku' => $this->productSku($id),
                'name' => $this->productName($id),
                'reasons' => $reasons,
            ], $this->blocked(), array_keys($this->blocked())),
            'issues' => array_map(fn (Issue $i) => $i->toArray(), $this->issues()),
        ];
    }
}
