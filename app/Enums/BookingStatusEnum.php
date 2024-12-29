<?php

namespace App\Enums;

enum BookingStatusEnum : int
{
    case pending = 1;

    case confirmed = 2;

    case cancelled = 3;
}
