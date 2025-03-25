<?php

namespace App\Repositories\ProductDetail;

use App\Models\ProductDetail;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;

class ProductDetailRepository extends BaseRepository implements IProductDetailRepository
{

    /**
     * BaseRepository constructor
     *
     * @param Model $model
     */

    public function __construct(ProductDetail $model)
    {
        $this->model = $model;
    }

    /**
     * get ProductDetailId By ProductId With ColorId And SizeId
     *
     * @param  int $productId
     * @param  int $color
     * @param  int $size
     * @return int
     */
    public function getProductDetailIdByProductIdWithColorIdAndSizeId(int $productId, int $colorId, int $sizeId): int
    {
        return $this->model->where('product_id', $productId)->where('color_attribute_id', $colorId)->where('size_attribute_id', $sizeId)->first()->id;
    }

}
