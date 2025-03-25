<?php

namespace App\Repositories\ProductAttribute;

use App\Helpers\PaginationHelper;
use App\Models\ProductAttribute;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;

class ProductAttributeRepository extends BaseRepository implements IProductAttributeRepository
{
    use PaginationHelper;

    /**
     * BaseRepository constructor
     *
     * @param Model $model
     */

    public function __construct(ProductAttribute $model)
    {
        $this->model = $model;
    }

    /**
     * Product attribute list with keyword
     *
     * @param  string $keyword
     * @return array
     */
    public function productAttributeListWithFilter(?string $keyword = null): array
    {
        $queryBuilder = $this->model->newQuery();
        if ($keyword !== null) {
            $queryBuilder->where('type', 'like', '%' . $keyword . '%');
        }

        $attributes = $queryBuilder->orderBy('id', 'desc')->get();
        $perPage    = $paginationOptions['perPage'] ?? null;
        $page       = $paginationOptions['page'] ?? 1;

        return $this->paginateCollection($attributes, $perPage, $page);
    }

}
