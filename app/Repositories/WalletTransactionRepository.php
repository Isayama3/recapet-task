<?php

namespace App\Repositories;

use App\Base\Repositories\BaseRepository;
use App\Models\WalletTransaction;

class WalletTransactionRepository extends BaseRepository
{
    /**
     * WalletTransactionRepository constructor.
     * @param WalletTransaction $model
     */
    public function __construct(WalletTransaction $model)
    {
        parent::__construct($model);
    }
}
