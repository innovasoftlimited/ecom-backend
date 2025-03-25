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
    public function orderListWithFilter(?string $keyword = null): array
    {
        $queryBuilder = $this->model->newQuery();
        if ($keyword !== null) {
            $queryBuilder->where('invoice_no', 'like', '%' . $keyword . '%');
        }

        $orders  = $queryBuilder->orderBy('id', 'desc')->with('orderDetails.product')->get();
        $perPage = $paginationOptions['perPage'] ?? null;
        $page    = $paginationOptions['page'] ?? 1;

        return $this->paginateCollection($orders, $perPage, $page);
    }

}
