<?php

namespace App\Http\Controllers\Api\User\Wallet;

use App\Base\Controllers\BaseApiController;
use App\Http\Requests\Api\User\FundWalletRequest;
use App\Http\Requests\Api\User\TransferMoneyRequest;
use App\Http\Requests\Api\User\WalletRequest as FormRequest;
use App\Http\Resources\WalletResource as Resource;
use App\Models\User;
use App\Models\Wallet as Model;
use App\Services\WalletService as Service;

class WalletController extends BaseApiController
{
    protected $WalletService;

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

        $this->WalletService = $service;
        $this->WalletService->setIndexRelations([]);
        $this->WalletService->setOneItemRelations(['transactions', 'transactions.status', 'transactions.type']);
        $this->WalletService->setCustomWhen($this->customWhen());
    }

    public function customWhen(): array
    {
        return [
            'condition' => true,
            'callback' => function ($q) {
                return $q->where('user_id', auth('user-api')->id());
            },
        ];
    }

    public function getAuthUserWallet()
    {
        $wallet = $this->WalletService->getUserWallet(auth('user-api')->id());
        return $this->respondWithModelData($this->resource::make($wallet));
    }

    public function fundAuthUserWallet(FundWalletRequest $request)
    {
        $funding_process = $this->WalletService->fundWallet(wallet: auth('user-api')->user()->wallet ,amount: $request->validated()['amount']);
        if ($funding_process) {
            return $this->respondWithSuccess('Fund wallet successfully');
        }

        return $this->respondWithError('Failed to fund wallet');
    }

    public function transferMoney(TransferMoneyRequest $request)
    {
        $recipient_wallet = User::where('phone', $request->validated()['recipient_phone'])->first()->wallet;
        $sender_wallet = auth('user-api')->user()->wallet;

        $transfer_process = $this->WalletService->transferP2P(
            wallet_from: $sender_wallet,
            wallet_to: $recipient_wallet,
            amount: $request->validated()['amount']
        );

        if ($transfer_process) {
            return $this->respondWithSuccess('Transfer money successfully');
        }

        return $this->respondWithError('Failed to transfer money');
    }
}
