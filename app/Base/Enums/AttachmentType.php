<?php

namespace App\Base\Enums;

use App\Base\Traits\Custom\EnumCustom;

enum WalletTransactionStatus: string
{
    use EnumCustom;
    case IMAGE = 'image';
    case VIDEO = 'video';
    case DOC = 'doc';
}
