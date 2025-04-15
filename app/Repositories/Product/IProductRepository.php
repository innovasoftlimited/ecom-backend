<?php

namespace App\Repositories\Product;

use App\Repositories\IBaseRepository;

interface IProductRepository extends IBaseRepository
{
    /**
     * @param string $keyword
     * @param  array $paginationOptions
     * @return array
     */
    public function productListWithFilter(string $keyword, array $paginationOptions = []): array;

    /**
     * @param string $keyword
     * @return array
     */
    public function getProductListByName(string $keyword): array;

}
