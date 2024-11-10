<?php

namespace App\Http\Controllers\Api\User\Wallet;

use App\Base\Controllers\BaseApiController;
use App\Http\Requests\Api\User\WalletTransactionRequest as FormRequest;
use App\Models\WalletTransaction as Model;
use App\Http\Resources\WalletTransactionResource as Resource;
use App\Services\WalletTransactionService as Service;

class WalletTransactionController extends BaseApiController
{
    protected $WalletTransactionService;

    public function __construct(
        FormRequest $request,
        Model $model,
        Service $service,
    ) {
        parent::__construct(
            $request,
            $model,
            new Resource($model),
            $service,
            hasDelete: false,
        );

        $this->WalletTransactionService = $service;
        $this->WalletTransactionService->setIndexRelations(['status']);
        $this->WalletTransactionService->setOneItemRelations([]);
        $this->WalletTransactionService->setCustomWhen($this->customWhen());
    }

    public function customWhen(): array
    {
        return [
            'condition' => true,
            'callback' => function ($q) {
                return $q->where('wallet_id', auth('user-api')->user()->wallet->id);
            },
        ];
    }
}
