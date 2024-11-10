<?php

namespace App\Repositories;

use App\Base\Repositories\BaseRepository;
use App\Models\Wallet;

class WalletRepository extends BaseRepository
{
    /**
     * WalletRepository constructor.
     * @param Wallet $model
     */
    public function __construct(Wallet $model)
    {
        parent::__construct($model);
    }

    public function getUserWallet($user_id,$relations = [])
    {
        return $this->model->where('user_id', $user_id)->with($relations)->first();
    }
}
