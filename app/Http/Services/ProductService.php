<?php
namespace App\Http\Services;

use App\Models\Product;
use App\Repositories\ProductDetail\IProductDetailRepository;
use App\Repositories\Product\IProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(
        private IProductRepository $productRepository,
        private IProductDetailRepository $productDetailRepository,
    ) {}

    /**
     * Create or Update Product
     *
     * @param  array $data
     * @param  int $userId
     * @return array
     */
    public function createUpdateProduct(array $data, int $userId): array
    {
        try {
            if (!Storage::exists('public/uploads')) {
                Storage::makeDirectory('public/uploads', 0777, true);
            }

            $directory = 'uploads/products/' . now()->format('Y/m/d');
            DB::beginTransaction();

            $productDetails = data_get($data, 'product_details');
            $file           = data_get($data, 'thumb_image');
            if (!is_null($file)) {
                $fileName = Str::random(20) . '_' . $file->getClientOriginalName();
                $path     = $file->storeAs($directory, $fileName, 'public');
                $url      = '/storage/' . $path;
            } else {
                $url = null;
            }

            $productData = [
                'name'        => data_get($data, 'name'),
                'description' => data_get($data, 'description'),
                'category_id' => data_get($data, 'category_id'),
                'brand_id'    => data_get($data, 'brand_id'),
                'thumb_image' => $url,
                'featured'    => data_get($data, 'featured'),
                'best_seller' => data_get($data, 'best_seller'),
                'unit_price'  => data_get($data, 'unit_price'),
                'is_active'   => data_get($data, 'is_active'),
            ];
            if (isset($data['id'])) {
                $product = $this->productRepository->find($data['id']);
                if (!$product) {
                    throw new \Exception("Product not found with ID: " . $data['id']);
                }

                $product = $this->productRepository->update($data['id'], [
                     ...$productData,
                ]);

                $this->updateProductDetails($productDetails, $product);

            } else {
                $product = $this->productRepository->create([
                     ...$productData,
                ]);
                $this->createProductDetails(data_get($data, 'product_details'), $product->id);

            }

            DB::commit();

            $product->load([
                'productDetails.size',
                'productDetails.color',
                'category.parent',
                'brand',
            ]);
            return $product->toArray();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Create product details
     *
     * @param  array $data
     * @param  int $productId
     * @return void
     */
    private function createProductDetails(array $data, int $productId): void
    {
        $directory = 'uploads/products/' . now()->format('Y/m/d');
        foreach ($data as $pd) {
            $file = $pd['image'];
            if (!is_null($file)) {
                $fileName = Str::random(20) . '_' . $file->getClientOriginalName();
                $path     = $file->storeAs($directory, $fileName, 'public');
                $url      = '/storage/' . $path;
            } else {
                $url = null;
            }
            $this->productDetailRepository->create([
                'product_id'         => $productId,
                'size_attribute_id'  => $pd['size_attribute_id'],
                'color_attribute_id' => $pd['color_attribute_id'],
                'sku'                => $pd['sku'],
                'unit_price'         => $pd['unit_price'],
                'quantity'           => $pd['quantity'],
                'image'              => $url,
            ]);

        }
    }

    /**
     * Update product details
     *
     * @param  array $data
     * @param  Product $product
     * @return void
     */
    private function updateProductDetails(array $data, Product $product): void
    {
        $existingProductDetailsIds = $product->productDetails()->pluck('id');
        $payloadProductDetailsIds  = collect($data)->pluck('id')->filter();
        $productDetailsIdsToRemove = $existingProductDetailsIds->diff($payloadProductDetailsIds);
        if (!empty($productDetailsIdsToRemove)) {
            foreach ($productDetailsIdsToRemove as $productDetailsId) {
                $this->productDetailRepository->delete($productDetailsId);
            }
        }
        $directory = 'uploads/products/' . now()->format('Y/m/d');
        foreach ($data as $pd) {

            if (isset($pd['id'])) {
                $productDetailsData = $this->productDetailRepository->find($pd['id']);

                if (!$productDetailsData) {
                    throw new \Exception("Product details not found with ID: " . $pd['id']);
                }
                $file = $pd['image'];
                if (!is_null($file)) {
                    $fileName = Str::random(20) . '_' . $file->getClientOriginalName();
                    $path     = $file->storeAs($directory, $fileName, 'public');
                    $url      = '/storage/' . $path;
                } else {
                    $url = null;
                }
                $this->productDetailRepository->update($pd['id'], [
                    'product_id'         => $product->id,
                    'size_attribute_id'  => $pd['size_attribute_id'],
                    'color_attribute_id' => $pd['color_attribute_id'],
                    'sku'                => $pd['sku'],
                    'unit_price'         => $pd['unit_price'],
                    'quantity'           => $pd['quantity'],
                    'image'              => $url,
                ]);
            } else {
                $file = $pd['image'];
                if (!is_null($file)) {
                    $fileName = Str::random(20) . '_' . $file->getClientOriginalName();
                    $path     = $file->storeAs($directory, $fileName, 'public');
                    $url      = '/storage/' . $path;
                } else {
                    $url = null;
                }
                $this->productDetailRepository->create([
                    'product_id'         => $product->id,
                    'size_attribute_id'  => $pd['size_attribute_id'],
                    'color_attribute_id' => $pd['color_attribute_id'],
                    'sku'                => $pd['sku'],
                    'unit_price'         => $pd['unit_price'],
                    'quantity'           => $pd['quantity'],
                    'image'              => $url,
                ]);

            }
        }
    }

}
