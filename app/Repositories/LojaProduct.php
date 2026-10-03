<?php

namespace App\Repositories;

use App\Domain\Catalog\Catalog;
use App\Support\Money;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Query facade over the Catalog (filters, sorting, pagination). It holds no
 * data of its own: every result comes from App\Domain\Catalog\Catalog, the
 * single source shared with the cart, checkout, JSON-LD and the feed.
 */
class LojaProduct
{
    protected Collection $items;

    public function __construct()
    {
        $this->items = app(Catalog::class)->all();
    }

    public static function query(): self
    {
        return new self();
    }

    public function where(string $key, $value): self
    {
        $this->items = $this->items->where($key, $value);

        return $this;
    }

    public function search(string $term): self
    {
        $term = mb_strtolower($term);

        $this->items = $this->items->filter(function ($item) use ($term) {
            return str_contains(mb_strtolower($item['title'] ?? ''), $term)
                || str_contains(mb_strtolower($item['slug'] ?? ''), $term);
        });

        return $this;
    }

    public function orderBy(string $key, string $direction = 'asc'): self
    {
        $value = $key === 'price'
            ? fn ($item) => Money::toFloat($item['price'] ?? 0)
            : $key;

        $this->items = $direction === 'asc'
            ? $this->items->sortBy($value)
            : $this->items->sortByDesc($value);

        return $this;
    }

    public function applyFilters(array $filters = []): self
    {
        if (! empty($filters['category'])) {
            $this->items = $this->items->where('category', $filters['category']);
        }

        if (isset($filters['min_price'], $filters['max_price'])) {
            $this->items = $this->items->filter(function ($item) use ($filters) {
                $price = Money::toFloat($item['price'] ?? 0);

                return $price >= $filters['min_price'] && $price <= $filters['max_price'];
            });
        }

        if (! empty($filters['in_stock'])) {
            $this->items = $this->items->where('in_stock', true);
        }

        if (! empty($filters['stock']) && $filters['stock'] === 'instock') {
            $this->items = $this->items->where('in_stock', true);
        }

        if (! empty($filters['colors']) && is_array($filters['colors'])) {
            $this->items = $this->items->filter(function ($item) use ($filters) {
                return isset($item['color']) && in_array($item['color'], $filters['colors']);
            });
        }

        if (! empty($filters['search'])) {
            $this->search($filters['search']);
        }

        switch ($filters['orderby'] ?? null) {
            case 'price':
                $this->orderBy('price', 'asc');
                break;
            case 'price-desc':
                $this->orderBy('price', 'desc');
                break;
            case 'date':
                $this->orderBy('id', 'desc');
                break;
            case 'title':
                $this->orderBy('title', 'asc');
                break;
            default:
                $this->orderBy('id', 'asc');
        }

        return $this;
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        $page = max(1, (int) request('page', 1));

        $results = $this->items
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

        return new LengthAwarePaginator(
            $results,
            $this->items->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }

    public function get(): Collection
    {
        return $this->items->values();
    }

    public static function find($id): ?array
    {
        return app(Catalog::class)->find($id);
    }
}
