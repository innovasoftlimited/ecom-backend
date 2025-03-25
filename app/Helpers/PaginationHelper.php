<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

trait PaginationHelper
{
    /**
     * Paginate a collection of items.
     *
     * @param Collection $items
     * @param int|null $perPage
     * @param int|null $currentPage
     * @return array
     */
    public function paginateCollection(Collection $items, ?int $perPage = null, ?int $currentPage = null): array
    {
        if ($perPage === null || $perPage === 0) {
            return [
                'data' => $items->values()->toArray(),
                'total' => $items->count(),
                'per_page' => $items->count(),
                'current_page' => 1,
                'total_pages' => 1,
            ];
        }

        $currentPage = $currentPage ?: LengthAwarePaginator::resolveCurrentPage();

        $offset = ($currentPage - 1) * $perPage;
        $paginatedItems = $items->slice($offset, $perPage);
        $total = $items->count();
        $totalPages = ceil($total / $perPage);
        return [
            'data' => $paginatedItems->values()->toArray(),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
        ];
    }

    /**
     * Apply sorting to a collection.
     *
     * @param Collection $items
     * @param string $column
     * @param string $direction
     * @return Collection
     */
    public function sortCollection(Collection $items, string $column, string $direction = 'asc'): Collection
    {
        return $items->sortBy($column, SORT_REGULAR, $direction === 'desc');
    }

    /**
     * Apply filtering to a collection.
     *
     * @param Collection $items
     * @param array $filters
     * @return Collection
     */
    public function filterCollection(Collection $items, array $filters): Collection
    {
        foreach ($filters as $column => $value) {
            $items = $items->where($column, $value);
        }

        return $items;
    }

    /**
     * Get pagination options from request query parameters.
     *
     * @return array
     */
    public function paginationOptionsFromRequest(): array
    {
        return [
            'perPage' => Request::query('perPage', null),
            'page' => Request::query('page', 1),
            'sortColumn' => Request::query('sortColumn', 'id'),
            'sortDirection' => Request::query('sortDirection', 'asc'),
            'filters' => Request::query('filters', []),
        ];
    }
}
