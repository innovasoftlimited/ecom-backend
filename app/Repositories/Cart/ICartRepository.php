<?php

namespace App\Repositories\Cart;

use App\Repositories\IBaseRepository;

interface ICartRepository extends IBaseRepository
{
    /**
     *
     * @param int $userId
     * @return array
     */
    public function getCartList(int $userId): array;

}
