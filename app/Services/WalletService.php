<?php

namespace App\Services;

use App\Base\Services\BaseService;
use App\Enums\WalletTransactionStatus;
use App\Enums\WalletTransactionType;
use App\Models\Wallet;
use App\Repositories\WalletRepository;
use App\Repositories\WalletTransactionRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WalletService extends BaseService
{
    protected WalletRepository $WalletRepository;
    protected WalletTransactionRepository $WalletTransactionRepository;

    public function __construct(WalletRepository $WalletRepository, WalletTransactionRepository $WalletTransactionRepository)
    {
        parent::__construct($WalletRepository);
        $this->WalletRepository = $WalletRepository;
        $this->WalletTransactionRepository = $WalletTransactionRepository;
    }

    public function getUserWallet(int $user_id): Model
    {
        $this->repository->setRelations($this->getOneItemRelations());
        $this->repository->setRelationWithSpecificCount('transactions', 10);
        return $this->repository->findOrFail($user_id);
    }

    public function fundWallet(Wallet $wallet, float $amount): bool
    {
        DB::beginTransaction();
        try {
            $this->WalletRepository->update($wallet->id, ['balance' => $wallet->balance + $amount]);
            $this->WalletTransactionRepository->create([
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'type_id' => WalletTransactionType::FUNDING->value,
                'status_id' => WalletTransactionStatus::SUCCESS->value,
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            $this->WalletTransactionRepository->create([
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'type_id' => WalletTransactionType::FUNDING->value,
                'status_id' => WalletTransactionStatus::FAILED->value,
            ]);

            return false;
        }
    }

    public function transferP2P(Wallet $wallet_from, Wallet $wallet_to, float $amount)
    {
        DB::beginTransaction();

        try {
            $fee = $this->calculateTransferFee($amount);
            $total_amount_after_fee = $amount + $fee;

            if ($wallet_from->balance < $total_amount_after_fee) {
                $this->throwHttpExceptionForWebAndApi(__('main.Insufficient balance'), 422);
            }

            $this->WalletRepository->update($wallet_from->id, [
                'balance' => $wallet_from->balance - $total_amount_after_fee,
            ]);

            $this->WalletRepository->update($wallet_to->id, [
                'balance' => $wallet_to->balance + $amount,
            ]);

            $this->WalletTransactionRepository->create([
                'wallet_id' => $wallet_from->id,
                'recipient_wallet_id' => $wallet_to->id,
                'amount' => $amount,
                'fee' => $fee,
                'type_id' => WalletTransactionType::TRANSFER->value,
                'status_id' => WalletTransactionStatus::SUCCESS->value,
            ]);

            $this->WalletTransactionRepository->create([
                'wallet_id' => $wallet_to->id,
                'amount' => $amount,
                'type_id' => WalletTransactionType::RECEIVING->value,
                'status_id' => WalletTransactionStatus::SUCCESS->value,
            ]);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->WalletTransactionRepository->create([
                'wallet_id' => $wallet_from->id,
                'recipient_wallet_id' => $wallet_to->id,
                'amount' => $amount,
                'fee' => $fee,
                'type_id' => WalletTransactionType::TRANSFER->value,
                'status_id' => WalletTransactionStatus::FAILED->value,
            ]);

            return false;
        }
    }

    private function calculateTransferFee(float $amount): float
    {
        if ($amount > 25) {
            return 2.5 + (0.1 * $amount);
        }

        return 0;
    }
}
