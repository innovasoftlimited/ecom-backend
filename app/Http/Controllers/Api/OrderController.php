<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\OrderService;
use App\Repositories\Order\IOrderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends BaseController
{
    /**
     * __construct
     *
     * @param  IOrderRepository $orderRepository
     * @return void
     */
    public function __construct(private OrderService $orderService, private readonly IOrderRepository $orderRepository)
    {
    }

    /**
     *
     * Get orders list
     *
     * @return JsonResponse
     */
    public function getOrderList(Request $request): JsonResponse
    {

        $keyword = $request->input('invoice_no');

        $result = $this->orderRepository->orderListWithFilter($keyword);

        return $this->successWithPagination($result, "Order list retrieved successfully");
    }

}
