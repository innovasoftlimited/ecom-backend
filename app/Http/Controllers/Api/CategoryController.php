<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CategoryCreateOrUpdateRequest;
use App\Repositories\Category\ICategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends BaseController
{
    /**
     * __construct
     *
     * @param  ICategoryRepository $categoryRepository
     * @return void
     */
    public function __construct(private readonly ICategoryRepository $categoryRepository)
    {
    }

    /**
     *
     * Store category or update
     *
     * @param  CategoryCreateOrUpdateRequest $request
     * @return JsonResponse
     */
    public function categoryStoreOrUpdate(CategoryCreateOrUpdateRequest $request): JsonResponse
    {
        try {
            if ($request->filled('parent_id')) {
                $categoryExists = $this->categoryRepository->find($request->parent_id);
                if (!$categoryExists) {
                    throw new \Exception("This parent id does not exist.");
                }
            }

            $categoryData = [
                'name'      => $request->name,
                'parent_id' => $request->parent_id ?? null,
                'is_active' => $request->is_active,
            ];

            if ($request->filled('id')) {
                $category = $this->categoryRepository->update($request->id, $categoryData);
                $category->load('parent');
                return $this->success($category->toArray(), "Category updated successfully");
            }

            $category = $this->categoryRepository->create($categoryData);
            $category->load('parent');
            return $this->success($category->toArray(), "Category created successfully");

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *
     * Get category list
     *
     * @return JsonResponse
     */
    public function getCategoryList(Request $request): JsonResponse
    {

        $keyword = $request->input('name');

        $result = $this->categoryRepository->categoryListWithFilter($keyword);

        return $this->successWithPagination($result, "Category list retrieved successfully");
    }

    /**
     *
     * category delete
     *
     * @param  integer $id
     * @return JsonResponse
     */
    public function deleteCategory(int $id): JsonResponse
    {
        try {
            $category = $this->categoryRepository->find($id);
            if (!$category) {
                throw new \Exception("Category not found with ID: " . $id);
            }
            if (is_null($category->parent_id)) {
                throw new \Exception("This is a parent category. Kindly delete the child categories first!");
            }
            $this->categoryRepository->delete($id);
            return $this->success([], "Category deleted successfully");

        } catch (\Exception $e) {
            return $this->error('Error', [$e->getMessage()]);
        }
    }

}
