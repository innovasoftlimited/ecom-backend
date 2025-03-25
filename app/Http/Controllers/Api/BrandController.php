<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\BrandCreateOrUpdateRequest;
use App\Repositories\Brand\IBrandRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BrandController extends BaseController
{
    /**
     * __construct
     *
     * @param  IBrandRepository $brandRepository
     * @return void
     */
    public function __construct(private readonly IBrandRepository $brandRepository)
    {
    }

    /**
     *
     * Store brand or update
     *
     * @param  BrandCreateOrUpdateRequest $request
     * @return JsonResponse
     */
    public function brandStoreOrUpdate(BrandCreateOrUpdateRequest $request): JsonResponse
    {
        try {

            $brandData = [
                'name'      => $request->name,
                'is_active' => $request->is_active,
            ];

            if ($request->filled('id')) {

                $brand = $this->brandRepository->update($request->id, $brandData);

                return $this->success($brand->toArray(), "Brand updated successfully");
            }

            $brand = $this->brandRepository->create($brandData);
            return $this->success($brand->toArray(), "Brand created successfully");

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *
     * Get brand list
     *
     * @return JsonResponse
     */
    public function getBrandList(Request $request): JsonResponse
    {

        $keyword = $request->input('name');

        $result = $this->brandRepository->brandListWithFilter($keyword);

        return $this->successWithPagination($result, "Brand list retrieved successfully");
    }

    /**
     *
     * brand delete
     *
     * @param  integer $id
     * @return JsonResponse
     */
    public function deleteBrand(int $id): JsonResponse
    {
        try {
            $brand = $this->brandRepository->find($id);
            if (!$brand) {
                throw new \Exception("Brand not found with ID: " . $id);
            }

            $this->brandRepository->delete($id);
            return $this->success([], "Brand deleted successfully");

        } catch (\Exception $e) {
            return $this->error('Error', [$e->getMessage()]);
        }
    }

}
