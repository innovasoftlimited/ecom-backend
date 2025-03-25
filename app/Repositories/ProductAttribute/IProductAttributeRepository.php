<?php

namespace App\Repositories\ProductAttribute;

use App\Repositories\IBaseRepository;

interface IProductAttributeRepository extends IBaseRepository
{
    /**
     * @param string $keyword
     * @return array
     */
    public function productAttributeListWithFilter(string $keyword): array;

}
