<?php

namespace Tests\Feature\Wallet;


use App\Models\User;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WalletServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected $walletService;
    protected $sender;
    protected $sender_wallet;
    protected $recipient;
    protected $recipient_wallet;
    public function setUp(): void
    {
        parent::setUp();
        $this->walletService = $this->app->make(WalletService::class);

        $this->sender = User::factory()->create();
        $this->sender_wallet = Wallet::factory()->create(['user_id' => $this->sender->id, 'balance' => 1000]);
        $this->recipient = User::factory()->create();
        $this->recipient_wallet = Wallet::factory()->create(['user_id' => $this->recipient->id, 'balance' => 0]);

    }

    public function testFundWallet()
    {
        $amount = 100;
        $result = $this->walletService->fundWallet($this->sender_wallet, $amount);
        $this->assertTrue($result);

        $this->assertDatabaseHas('wallets', [
            'id' => $this->sender_wallet->id,
            'balance' => $this->sender_wallet->balance + $amount,
        ]);
    }

    public function testTransferP2PSuccess()
    {
        $amount = 50;

        $fee = $this->walletService->calculateTransferFee($amount);
        $amount_after_set_fee = $amount + $fee;
        
        $result = $this->walletService->transferP2P($this->sender_wallet, $this->recipient_wallet, $amount , $fee);
        $this->assertTrue($result);

        $this->assertDatabaseHas('wallets', ['id' => $this->sender_wallet->id, 'balance' => $this->sender_wallet->balance - $amount_after_set_fee]);
        $this->assertDatabaseHas('wallets', ['id' => $this->recipient_wallet->id, 'balance' => $this->recipient_wallet->balance + $amount]);
    }

    public function testTransferP2PInsufficientBalance()
    {
        $amount = $this->sender_wallet->balance + 100;
        $fee = $this->walletService->calculateTransferFee($amount);

        $result = $this->walletService->transferP2P($this->sender_wallet, $this->recipient_wallet, $amount ,$fee);
        $this->assertFalse($result);
    }
}