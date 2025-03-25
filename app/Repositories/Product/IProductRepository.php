<?php

namespace App\Repositories\Product;

use App\Repositories\IBaseRepository;

interface IProductRepository extends IBaseRepository
{
    /**
     * @param string $keyword
     * @return array
     */
    public function productListWithFilter(string $keyword): array;

}
