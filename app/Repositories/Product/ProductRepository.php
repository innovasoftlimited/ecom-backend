<?php

namespace App\Repositories\Product;

use App\Helpers\PaginationHelper;
use App\Models\Product;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;

class ProductRepository extends BaseRepository implements IProductRepository
{
    use PaginationHelper;

    /**
     * BaseRepository constructor
     *
     * @param Model $model
     */

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    /**
     * Product list with keyword
     *
     * @param  string $keyword
     * @param  array $paginationOptions
     * @return array
     */
    public function productListWithFilter(?string $keyword = null, array $paginationOptions = []): array
    {
        $queryBuilder = $this->model->newQuery();
        if ($keyword !== null) {
            $queryBuilder->where('name', 'like', '%' . $keyword . '%');
        }

        $products = $queryBuilder->orderBy('id', 'desc')->with('productDetails.size', 'productDetails.color', 'category.parent', 'brand')->get();
        $perPage  = $paginationOptions['perPage'] ?? null;
        $page     = $paginationOptions['page'] ?? 1;

        return $this->paginateCollection($products, $perPage, $page);
    }
    /**
     * Product list by name
     *
     * @param  string $keyword
     * @return array
     */
    public function getProductListByName(?string $keyword = null): array
    {
        $products     = [];
        $queryBuilder = $this->model->newQuery();
        if ($keyword !== null) {
            $queryBuilder->where('name', 'like', '%' . $keyword . '%');
            $products = $queryBuilder->orderBy('id', 'desc')->select('id', 'name', 'thumb_image', 'unit_price')->get()->toArray();
        }
        return $products;

    }

}
