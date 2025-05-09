<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatusEnum;
use App\Helpers\PaginationHelper;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Services\OrderService;
use App\Repositories\Order\IOrderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends BaseController
{
    use PaginationHelper;
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
        try {
            $user = Auth::user();
            if ($user->type != 'admin') {
                throw new \Exception("You cannot access this order list");
            }
            $keyword = $request->input('invoice_no');

            $result = $this->orderRepository->orderListWithFilter($keyword, $this->paginationOptionsFromRequest());

            return $this->successWithPagination($result, "Order list retrieved successfully");
        } catch (\Exception $e) {
            return $this->error('Error', [$e->getMessage()]);
        }
    }

    /**
     * Get a single order by ID
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $order = $this->orderRepository->find($id);
        if (!$order) {
            throw new \Exception("Order not found with ID: " . $id);
        }
        $result = $order->load('orderDetails.product.size', 'orderDetails.product.color');
        return $this->success($result->toArray(), "Order details retrieved successfully");
    }

    // Create a new order
    public function store(OrderCreateRequest $request)
    {

        try {
            $result = $this->orderService->placeOrder($request->json()->all(), Auth::id());
            return $this->success($result, "Order placed successfully");

        } catch (\Exception $e) {
            return $this->error('Error', [$e->getMessage()]);
        }
    }

    // Update status of an existing order
    public function update(Request $request, $id)
    {
        try {
            $order = $this->orderRepository->find($id);
            if (!$order) {
                throw new \Exception("Order not found with ID: " . $id);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'required|integer',
            ]);

            $order->update([
                'status' => $request->status,
            ]);

            $order->load([
                'orderDetails.product.size',
                'orderDetails.product.color',
            ]);

            return $this->success($order->toArray(), "Order status updated successfully");

        } catch (\Exception $e) {
            return $this->error('Error', [$e->getMessage()]);
        }
    }

    // Delete an order
    public function destroy($id)
    {
        try {
            $order = $this->orderRepository->find($id);
            if (!$order) {
                throw new \Exception("Order not found with ID: " . $id);
            }

            DB::beginTransaction();
            $order->orderDetails()->delete();
            $order->delete();
            DB::commit();

            return $this->success([], "Order deleted successfully");

        } catch (\Exception $e) {
            return $this->error('Error', [$e->getMessage()]);
        }
    }

    /**
     * Get order status by ID
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function trackOrderStatus(int $id): JsonResponse
    {
        try {
            $order = $this->orderRepository->find($id);
            if (!$order) {
                throw new \Exception("Order not found with ID: " . $id);
            }
            $currentStatusValue = $order->status;

            // List all statuses in order
            $allStatuses = [
                OrderStatusEnum::PENDING,
                OrderStatusEnum::PROCESSING,
                OrderStatusEnum::SHIPPED,
                OrderStatusEnum::DELIVERED,
            ];

            // Include only statuses up to and including the current one
            $completedStatuses = array_filter($allStatuses, function ($status) use ($currentStatusValue) {
                return $status->value <= $currentStatusValue;
            });

            // Format response
            $result = collect($completedStatuses)->map(function ($status) {
                return [
                    'code' => $status->value,
                    'name' => $status->name,
                ];
            })->values();

            return $this->success($result->toArray(), "Order status retrieved successfully");
        } catch (\Exception $e) {
            return $this->error('Error', [$e->getMessage()]);
        }
    }

    /**
     *
     * Get orders list by user
     *
     * @return JsonResponse
     */
    public function getOrderListByUserId(): JsonResponse
    {

        $result = $this->orderRepository->orderListByUserId(Auth::id(), $this->paginationOptionsFromRequest());

        return $this->successWithPagination($result, "Order list retrieved successfully");
    }

}
