<?php
namespace App\Http\Services;

use App\Repositories\OrderDetail\IOrderDetailRepository;
use App\Repositories\Order\IOrderRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        private IOrderRepository $orderRepository,
        private IOrderDetailRepository $orderDetailRepository,
    ) {}

    /**
     * place order
     *
     * @param  array $data
     * @param  int $userId
     * @return array
     */
    public function placeOrder(array $data, int $userId): array
    {
        DB::beginTransaction();
        try {
            $order = $this->orderRepository->create([
                'invoice_no'  => data_get($data, 'invoice_no'),
                'user_id'     => $userId,
                'total_price' => data_get($data, 'total_price'),
                'status'      => data_get($data, 'status'),
            ]);

            $orderDetails = data_get($data, 'order_details');
            foreach ($orderDetails as $detail) {
                $this->orderDetailRepository->create([
                    'order_id'           => $order->id,
                    'product_details_id' => $detail['product_details_id'],
                    'quantity'           => $detail['quantity'],
                    'total_price'        => $detail['total_price'],
                ]);
            }

            DB::commit();
            $order->load([
                'orderDetails.product',
            ]);
            return $order->toArray();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
