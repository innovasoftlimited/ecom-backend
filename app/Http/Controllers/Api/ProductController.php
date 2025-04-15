<?php

namespace App\Http\Controllers\Api;

use App\Helpers\PaginationHelper;
use App\Http\Requests\ProductCreateOrUpdateRequest;
use App\Http\Services\ProductService;
use App\Repositories\Product\IProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProductController extends BaseController
{
    use PaginationHelper;
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

        $result = $this->productRepository->productListWithFilter($keyword, $this->paginationOptionsFromRequest());

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

    /**
     *
     * search product
     *
     * @return JsonResponse
     */
    public function searchProduct(Request $request): JsonResponse
    {

        $keyword = $request->input('name');

        $result = $this->productRepository->getProductListByName($keyword);

        return $this->success($result, "Product list retrieved successfully");
    }

    /**
     *
     * product delete
     *
     * @param  integer $id
     * @return JsonResponse
     */
    public function getProductById(int $id): JsonResponse
    {
        try {
            $product = $this->productRepository->find($id);
            if (!$product) {
                throw new \Exception("Product not found with ID: " . $id);
            }
            $result = $product->load('productDetails.size', 'productDetails.color', 'category.parent', 'brand');
            return $this->success([$result], "Product details retrieved successfully");

        } catch (\Exception $e) {
            return $this->error('Error', [$e->getMessage()]);
        }
    }

}
