<?php

namespace App\Repositories\Brand;

use App\Helpers\PaginationHelper;
use App\Models\Brand;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;

class BrandRepository extends BaseRepository implements IBrandRepository
{
    use PaginationHelper;

    /**
     * BaseRepository constructor
     *
     * @param Model $model
     */

    public function __construct(Brand $model)
    {
        $this->model = $model;
    }

    /**
     * Brand list with keyword
     *
     * @param  string $keyword
     * @return array
     */
    public function brandListWithFilter(?string $keyword = null): array
    {
        $queryBuilder = $this->model->newQuery();
        if ($keyword !== null) {
            $queryBuilder->where('name', 'like', '%' . $keyword . '%');
        }

        $brands  = $queryBuilder->orderBy('id', 'desc')->get();
        $perPage = $paginationOptions['perPage'] ?? null;
        $page    = $paginationOptions['page'] ?? 1;

        return $this->paginateCollection($brands, $perPage, $page);
    }

}
