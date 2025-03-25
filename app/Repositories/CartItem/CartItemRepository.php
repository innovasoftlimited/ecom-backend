<?php

namespace App\Repositories\CartItem;

use App\Models\CartItem;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;

class CartItemRepository extends BaseRepository implements ICartItemRepository
{

    /**
     * BaseRepository constructor
     *
     * @param Model $model
     */

    public function __construct(CartItem $model)
    {
        $this->model = $model;
    }

    /**
     * getCartItemByCartId
     *
     * @param  int $cartId
     * @param  int $itemId
     * @return null|CartItem
     */
    public function getCartItemByCartId(int $cartId, int $itemId): ?CartItem
    {
        return $this->model->where('cart_id', $cartId)->where('id', $itemId)->first();
    }

    /**
     * isCartItemExist
     *
     * @param  int $cartId
     * @return Bool
     */
    public function isCartItemExist(int $cartId): Bool
    {
        return $this->model->where('cart_id', $cartId)->exists();
    }

}
