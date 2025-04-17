<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProductAttributeCreateOrUpdateRequest;
use App\Repositories\ProductAttribute\IProductAttributeRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductAttributeController extends BaseController
{
    /**
     * __construct
     *
     * @param  IProductAttributeRepository $productAttributeRepository
     * @return void
     */
    public function __construct(private readonly IProductAttributeRepository $productAttributeRepository)
    {
    }

    /**
     *
     * Store product attribute or update
     *
     * @param  ProductAttributeCreateOrUpdateRequest $request
     * @return JsonResponse
     */
    public function attributeStoreOrUpdate(ProductAttributeCreateOrUpdateRequest $request): JsonResponse
    {
        try {

            $productAttributeData = [
                'type'      => $request->type,
                'value'     => $request->value,
                'is_active' => $request->is_active,
            ];

            if ($request->filled('id')) {

                $productAttribute = $this->productAttributeRepository->update($request->id, $productAttributeData);

                return $this->success($productAttribute->toArray(), "Product attribute updated successfully");
            }

            $productAttribute = $this->productAttributeRepository->create($productAttributeData);
            return $this->success($productAttribute->toArray(), "Product attribute created successfully");

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *
     * Get product attribute list
     *
     * @return JsonResponse
     */
    public function getProductAttributeList(Request $request): JsonResponse
    {

        $keyword = $request->input('type');

        $result = $this->productAttributeRepository->productAttributeListWithFilter($keyword);

        return $this->successWithPagination($result, "Product attribute list retrieved successfully");
    }

    /**
     *
     * Get size attribute list
     *
     * @return JsonResponse
     */
    public function getSizeAttributeList(): JsonResponse
    {

        $result = $this->productAttributeRepository->sizeAttributeList();

        return $this->success($result, "Size attribute list retrieved successfully");
    }
    /**
     *
     * Get color attribute list
     *
     * @return JsonResponse
     */
    public function getColorAttributeList(): JsonResponse
    {

        $result = $this->productAttributeRepository->colorAttributeList();

        return $this->success($result, "Color attribute list retrieved successfully");
    }

    /**
     *
     * product attribute delete
     *
     * @param  integer $id
     * @return JsonResponse
     */
    public function deleteProductAttribute(int $id): JsonResponse
    {
        try {
            $productAttribute = $this->productAttributeRepository->find($id);
            if (!$productAttribute) {
                throw new \Exception("Product attribute not found with ID: " . $id);
            }

            $this->productAttributeRepository->delete($id);
            return $this->success([], "Product attribute deleted successfully");

        } catch (\Exception $e) {
            return $this->error('Error', [$e->getMessage()]);
        }
    }

}
