<?php

namespace App\Providers;

use App\Repositories\Brand\BrandRepository;
use App\Repositories\Brand\IBrandRepository;
use App\Repositories\CartItem\CartItemRepository;
use App\Repositories\CartItem\ICartItemRepository;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Cart\ICartRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\ICategoryRepository;
use App\Repositories\OrderDetail\IOrderDetailRepository;
use App\Repositories\OrderDetail\OrderDetailRepository;
use App\Repositories\Order\IOrderRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\ProductAttribute\IProductAttributeRepository;
use App\Repositories\ProductAttribute\ProductAttributeRepository;
use App\Repositories\ProductDetail\IProductDetailRepository;
use App\Repositories\ProductDetail\ProductDetailRepository;
use App\Repositories\Product\IProductRepository;
use App\Repositories\Product\ProductRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(abstract :ICategoryRepository::class, concrete: CategoryRepository::class);
        $this->app->bind(abstract :IBrandRepository::class, concrete: BrandRepository::class);
        $this->app->bind(abstract :IProductAttributeRepository::class, concrete: ProductAttributeRepository::class);
        $this->app->bind(abstract :IProductRepository::class, concrete: ProductRepository::class);
        $this->app->bind(abstract :IProductDetailRepository::class, concrete: ProductDetailRepository::class);
        $this->app->bind(abstract :ICartRepository::class, concrete: CartRepository::class);
        $this->app->bind(abstract :ICartItemRepository::class, concrete: CartItemRepository::class);
        $this->app->bind(abstract :IOrderRepository::class, concrete: OrderRepository::class);
        $this->app->bind(abstract :IOrderDetailRepository::class, concrete: OrderDetailRepository::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
