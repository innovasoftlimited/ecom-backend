<?php

namespace App\Enums;

enum OrderStatusEnum: int {
    case PENDING    = 0;
    case PROCESSING = 1;
    case SHIPPED    = 2;
    case DELIVERED  = 3;
    case CANCELLED  = 4;
    case REFUNDED   = 5;
    case FAILED     = 6;
    case ON_HOLD    = 7;
    case RETURNED   = 8;
}
