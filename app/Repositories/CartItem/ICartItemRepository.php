<?php

namespace App\Repositories\CartItem;

use App\Models\CartItem;
use App\Repositories\IBaseRepository;

interface ICartItemRepository extends IBaseRepository
{
    /**
     * getCartItemByCartId
     *
     * @param  int $cartId
     * @param  int $itemId
     * @return null|CartItem
     */
    public function getCartItemByCartId(int $cartId, int $itemId): ?CartItem;

    /**
     * isCartItemExist
     *
     * @param  int $cartId
     * @return Bool
     */
    public function isCartItemExist(int $cartId): Bool;

}
