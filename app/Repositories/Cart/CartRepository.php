<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;

class CartRepository extends BaseRepository implements ICartRepository
{

    /**
     * BaseRepository constructor
     *
     * @param Model $model
     */

    public function __construct(Cart $model)
    {
        $this->model = $model;
    }

    /**
     * Cart list
     *
     * @param  int $userId
     * @return array
     */
    public function getCartList(int $userId): array
    {
        $cart = $this->model->with('cartItems.productDetails')->orderBy('id', 'desc')->where('user_id', $userId)->get();

        return $cart->toArray();
    }

}
