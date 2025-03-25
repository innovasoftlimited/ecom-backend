<?php
namespace App\Http\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Repositories\CartItem\ICartItemRepository;
use App\Repositories\Cart\ICartRepository;
use App\Repositories\ProductDetail\IProductDetailRepository;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function __construct(
        private ICartRepository $cartRepository,
        private ICartItemRepository $cartItemRepository,
        private IProductDetailRepository $productDetailRepository,
    ) {}

    /**
     * add items to cart
     *
     * @param  array $data
     * @param  int $userId
     * @return array
     */
    public function createUpdateCart(array $data, int $userId): array
    {
        try {
            DB::beginTransaction();

            $cartData = [
                'user_id'     => $userId,
                'total_price' => data_get($data, 'sub_total'),
            ];
            if (isset($data['id'])) {
                $cart = $this->cartRepository->find($data['id']);
                if (!$cart) {
                    throw new \Exception("Cart found with ID: " . $data['id']);
                }

                $cart = $this->cartRepository->update($data['id'], [
                     ...$cartData,
                ]);

                $this->updateCartItems(data_get($data, 'cart_items'), $cart);

            } else {
                $cart = $this->cartRepository->create([
                     ...$cartData,
                ]);
                $this->createCartItems(data_get($data, 'cart_items'), $cart->id);

            }

            DB::commit();

            $cart->load([
                'cartItems.productDetails.product',
                'cartItems.productDetails.size',
                'cartItems.productDetails.color',
            ]);
            return $cart->toArray();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Create cart items
     *
     * @param  array $data
     * @param  int $cartId
     * @return void
     */
    private function createCartItems(array $data, int $cartId): void
    {
        foreach ($data as $item) {
            $productId       = $item['product_id'];
            $sizeId          = $item['size_id'];
            $colorId         = $item['color_id'];
            $productDetailId = $this->productDetailRepository->getProductDetailIdByProductIdWithColorIdAndSizeId($productId, $colorId, $sizeId);

            if (!$productDetailId) {
                throw new \Exception("This product not found!");
            }

            $this->cartItemRepository->create([
                'cart_id'            => $cartId,
                'product_details_id' => $productDetailId,
                'quantity'           => $item['quantity'],
                'unit_price'         => $item['unit_price'],
            ]);

        }
    }

    /**
     * Update product details
     *
     * @param  array $data
     * @param  Product $product
     * @return void
     */
    private function updateCartItems(array $data, Cart $cart): void
    {
        $existingCartItemIds = $cart->cartItems()->pluck('id');
        $payloadCartItemIds  = collect($data)->pluck('id')->filter();
        $cartItemIdsToRemove = $existingCartItemIds->diff($payloadCartItemIds);
        if (!empty($cartItemIdsToRemove)) {
            foreach ($cartItemIdsToRemove as $cartItemId) {
                $this->productDetailRepository->delete($cartItemId);
            }
        }

        foreach ($data as $item) {
            $productId       = $item['product_id'];
            $sizeId          = $item['size_id'];
            $colorId         = $item['color_id'];
            $productDetailId = $this->productDetailRepository->getProductDetailIdByProductIdWithColorIdAndSizeId($productId, $colorId, $sizeId);

            if (!$productDetailId) {
                throw new \Exception("This product not found!");
            }

            if (isset($item['id'])) {
                $cartItemData = $this->cartItemRepository->find($item['id']);

                if (!$cartItemData) {
                    throw new \Exception("Cart item not found with ID: " . $item['id']);
                }

                $this->cartItemRepository->update($item['id'], [
                    'cart_id'            => $cart->id,
                    'product_details_id' => $productDetailId,
                    'quantity'           => $item['quantity'],
                    'unit_price'         => $item['unit_price'],
                ]);
            } else {

                $this->cartItemRepository->create([
                    'cart_id'            => $cart->id,
                    'product_details_id' => $productDetailId,
                    'quantity'           => $item['quantity'],
                    'unit_price'         => $item['unit_price'],
                ]);

            }
        }
    }

    /**
     * delete Cart item
     *
     * @param  int  $cartId
     * @param  int  $itemId
     * @return void
     */
    public function deleteCartItem(int $cartId, int $itemId)
    {
        $cartItem = $this->cartItemRepository->getCartItemByCartId($cartId, $itemId);

        if (is_null($cartItem)) {
            throw new \Exception("Item not found in cart");
        }

        // Delete the cart item
        $cartItem->delete();

        // Check if the cart has any remaining items
        $remainingItems = $this->cartItemRepository->isCartItemExist($cartId);

        if (!$remainingItems) {
            $this->cartRepository->delete($cartId);
        }
    }

}
