<?php

namespace App\Repositories\OrderDetail;

use App\Models\OrderDetail;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;

class OrderDetailRepository extends BaseRepository implements IOrderDetailRepository
{

    /**
     * BaseRepository constructor
     *
     * @param Model $model
     */

    public function __construct(OrderDetail $model)
    {
        $this->model = $model;
    }

}
