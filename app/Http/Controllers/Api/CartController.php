<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\CartService;
use App\Repositories\Cart\ICartRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CartController extends BaseController
{
    /**
     * __construct
     *
     * @param  ICartRepository $productRepository
     * @return void
     */
    public function __construct(private CartService $cartService, private readonly ICartRepository $cartRepository)
    {
    }

    /**
     *
     * add items to cart
     *
     * @return JsonResponse
     */
    public function addToCartOrUpdate(Request $request): JsonResponse
    {
        try {
            $result = $this->cartService->createUpdateCart($request->json()->all(), Auth::id());
            return $this->success($result, "Item added to cart successfully");

        } catch (\Exception $e) {
            return $this->error('Error', [$e->getMessage()]);
        }
    }

    /**
     *
     * Get products list
     *
     * @return JsonResponse
     */
    public function getCartList(): JsonResponse
    {

        $result = $this->cartRepository->getCartList(Auth::id());

        return $this->success($result, "Cart list retrieved successfully");
    }

    /**
     *
     * cart item delete
     *
     * @param  integer $cartId
     * @param  integer $itemId
     * @return JsonResponse
     */
    public function deleteCartItemWithCart(int $cartId, int $itemId): JsonResponse
    {
        try {
            $cart = $this->cartRepository->find($cartId);
            if (!$cart) {
                throw new \Exception("Cart not found with ID: " . $cartId);
            }

            $this->cartService->deleteCartItem($cartId, $itemId);
            return $this->success([], "Item deleted successfully");

        } catch (\Exception $e) {
            return $this->error('Error', [$e->getMessage()]);
        }
    }

}
