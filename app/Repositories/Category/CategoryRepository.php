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

        $categories = $queryBuilder->with('parent')->get();

        $allCategories = $categories->keyBy('id');

        $grouped = [];

        foreach ($categories as $category) {
            if ($category->parent_id === null || !isset($allCategories[$category->parent_id])) {
                continue;
            }

            $parent = $allCategories[$category->parent_id];

            if ($parent->parent_id === null) {
                $grandParentName = $parent->name;

                if (!isset($grouped[$grandParentName])) {
                    $grouped[$grandParentName] = [
                        'parent' => $grandParentName,
                        'child'  => [],
                    ];
                }

                $childIndex = null;
                foreach ($grouped[$grandParentName]['child'] as $index => $childItem) {
                    if ($childItem['name'] === $category->name) {
                        $childIndex = $index;
                        break;
                    }
                }

                if ($childIndex === null) {
                    $grouped[$grandParentName]['child'][] = [
                        'name'      => $category->name,
                        'sub_child' => [],
                    ];
                }
            } else {
                $grandParent = $allCategories[$parent->parent_id] ?? null;
                if (!$grandParent) {
                    continue;
                }

                $grandParentName = $grandParent->name;

                if (!isset($grouped[$grandParentName])) {
                    $grouped[$grandParentName] = [
                        'parent' => $grandParentName,
                        'child'  => [],
                    ];
                }

                foreach ($grouped[$grandParentName]['child'] as &$child) {
                    if ($child['name'] === $parent->name) {
                        $child['sub_child'][] = $category->name;
                        break;
                    }
                }
            }
        }

        return array_values($grouped);

    }

}
