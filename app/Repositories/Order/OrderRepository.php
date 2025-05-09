<?php

namespace App\Repositories\Order;

use App\Helpers\PaginationHelper;
use App\Models\Order;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;

class OrderRepository extends BaseRepository implements IOrderRepository
{
    use PaginationHelper;

    /**
     * BaseRepository constructor
     *
     * @param Model $model
     */

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    /**
     * Order list with keyword
     *
     * @param  string $keyword
     * @return array
     */
    public function orderListWithFilter(?string $keyword = null, array $paginationOptions = []): array
    {
        $queryBuilder = $this->model->newQuery();
        if ($keyword !== null) {
            $queryBuilder->where('invoice_no', 'like', '%' . $keyword . '%');
        }

        $orders  = $queryBuilder->orderBy('id', 'desc')->with('orderDetails.product.size', 'orderDetails.product.color')->get();
        $perPage = $paginationOptions['perPage'] ?? null;
        $page    = $paginationOptions['page'] ?? 1;

        return $this->paginateCollection($orders, $perPage, $page);
    }

    /**
     * Order list by userId
     *
     * @param  int $userId
     * @return array
     */
    public function orderListByUserId(int $userId, array $paginationOptions = []): array
    {

        $orders  = $this->model->where('user_id', $userId)->orderBy('id', 'desc')->with('orderDetails.product.size', 'orderDetails.product.color')->get();
        $perPage = $paginationOptions['perPage'] ?? null;
        $page    = $paginationOptions['page'] ?? 1;

        return $this->paginateCollection($orders, $perPage, $page);
    }

}
