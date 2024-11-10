<?php

namespace App\Services;

use App\Base\Services\BaseService;
use App\Repositories\WalletRepository;
use App\Repositories\WalletTransactionRepository;

class WalletTransactionService extends BaseService
{
    protected WalletTransactionRepository $WalletTransactionRepository;
    protected WalletRepository $WalletRepository;

    public function __construct(WalletTransactionRepository $WalletTransactionRepository, WalletRepository $WalletRepository)
    {
        parent::__construct($WalletTransactionRepository);
        $this->WalletTransactionRepository = $WalletTransactionRepository;
        $this->WalletRepository = $WalletRepository;
    }
}
