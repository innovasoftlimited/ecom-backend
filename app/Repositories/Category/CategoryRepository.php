<?php

namespace App\Repositories\Category;

use App\Helpers\PaginationHelper;
use App\Models\Category;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;

class CategoryRepository extends BaseRepository implements ICategoryRepository
{
    use PaginationHelper;

    /**
     * BaseRepository constructor
     *
     * @param Model $model
     */

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    /**
     * Category list with keyword
     *
     * @param  string $keyword
     * @return array
     */
    public function categoryListWithFilter(?string $keyword = null): array
    {
        $queryBuilder = $this->model->newQuery();
        if ($keyword !== null) {
            $queryBuilder->where('name', 'like', '%' . $keyword . '%');
        }

        $categories = $queryBuilder->with('parent')->orderBy('id', 'desc')->get();
        $perPage    = $paginationOptions['perPage'] ?? null;
        $page       = $paginationOptions['page'] ?? 1;

        return $this->paginateCollection($categories, $perPage, $page);
    }

}
