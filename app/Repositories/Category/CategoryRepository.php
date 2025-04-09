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

        // Index categories by ID for quick access
        $allCategories = $categories->keyBy('id');

        $grouped = [];

        foreach ($categories as $category) {
            if ($category->parent_id === null || !isset($allCategories[$category->parent_id])) {
                continue;
            }

            $parent = $allCategories[$category->parent_id];

            // Check if this parent also has a parent (i.e. grandparent exists)
            if ($parent->parent_id === null) {
                // grandparent level: root -> parent -> child
                $grandParentName = $parent->name;

                if (!isset($grouped[$grandParentName])) {
                    $grouped[$grandParentName] = [
                        'parent' => $grandParentName,
                        'child'  => [],
                    ];
                }

                // Check if this child already exists
                $childExists = false;
                foreach ($grouped[$grandParentName]['child'] as $child) {
                    if ($child['name'] === $category->name) {
                        $childExists = true;
                        break;
                    }
                }

                if (!$childExists) {
                    $grouped[$grandParentName]['child'][] = [
                        'name'      => $category->name,
                        'sub_child' => [],
                    ];
                }
            } else {
                // This is a sub_child (child of a child)
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

                // Find the correct child to append sub_child
                foreach ($grouped[$grandParentName]['child'] as &$child) {
                    if ($child['name'] === $parent->name) {
                        $child['sub_child'][] = [
                            'name' => $category->name,
                        ];
                        break;
                    }
                }
            }
        }

        return array_values($grouped);

    }

}
