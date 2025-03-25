<?php

namespace App\Repositories\Order;

use App\Repositories\IBaseRepository;

interface IOrderRepository extends IBaseRepository
{
    /**
     * @param string $keyword
     * @return array
     */
    public function orderListWithFilter(string $keyword): array;

}
