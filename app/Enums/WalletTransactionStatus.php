<?php

namespace App\Enums;

use App\Base\Traits\Custom\EnumCustom;

enum WalletTransactionStatus: int
{
    use EnumCustom;
    
    case SUCCESS        = 1;
    case FAILED         = 2;
}
