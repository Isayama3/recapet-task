<?php

namespace App\Enums;

use App\Base\Traits\Custom\EnumCustom;

enum WalletTransactionType: int
{
    use EnumCustom;

    case FUNDING       = 1;
    case TRANSFER   = 2;
    case RECEIVING    = 3;
}
