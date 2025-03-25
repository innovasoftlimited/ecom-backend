<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProductCreateOrUpdateRequest;
use App\Http\Services\ProductService;
use App\Repositories\Product\IProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProductController extends BaseController
{
    /**
     * __construct
     *
     * @param  IProductRepository $productRepository
     * @return void
     */
    public function __construct(private ProductService $productService, private readonly IProductRepository $productRepository)
    {
    }

    /**
     *
     * Store product or update
     *
     * @param  ProductCreateOrUpdateRequest $request
     * @return JsonResponse
     */
    public function productStoreOrUpdate(ProductCreateOrUpdateRequest $request): JsonResponse
    {
        try {
            $result = $this->productService->createUpdateProduct($request->json()->all(), Auth::id());
            return $this->success($result, "Product create or update successfully");

        } catch (\Exception $e) {
            return $this->error('Error', [$e->getMessage()]);
        }
    }

    /**
     *
     * Get products list
     *
     * @return JsonResponse
     */
    public function getProductList(Request $request): JsonResponse
    {

        $keyword = $request->input('name');

        $result = $this->productRepository->productListWithFilter($keyword);

        return $this->successWithPagination($result, "Product list retrieved successfully");
    }

    /**
     *
     * product delete
     *
     * @param  integer $id
     * @return JsonResponse
     */
    public function deleteProduct(int $id): JsonResponse
    {
        try {
            $product = $this->productRepository->find($id);
            if (!$product) {
                throw new \Exception("Product not found with ID: " . $id);
            }

            $this->productRepository->delete($id);
            return $this->success([], "Product deleted successfully");

        } catch (\Exception $e) {
            return $this->error('Error', [$e->getMessage()]);
        }
    }

}
