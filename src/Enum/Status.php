<?php

declare(strict_types=1);

namespace App\Enum;

enum Status : int
{
    case UNPURCHASED = 1;
    case PURCHASED = 2;
}
