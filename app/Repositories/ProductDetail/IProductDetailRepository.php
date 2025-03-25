<?php

namespace App\Repositories\ProductDetail;

use App\Repositories\IBaseRepository;

interface IProductDetailRepository extends IBaseRepository
{

    /**
     * get ProductDetailId By ProductId With ColorId And SizeId
     *
     * @param  int $productId
     * @param  int $colorId
     * @param  int $sizeId
     * @return int
     */
    public function getProductDetailIdByProductIdWithColorIdAndSizeId(int $productId, int $colorId, int $sizeId): int;

}
