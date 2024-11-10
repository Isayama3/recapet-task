<?php
namespace Tests\Feature\Wallet;

use App\Models\User;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $walletService;
    protected $sender;
    protected $sender_wallet;
    protected $recipient;
    protected $recipient_wallet;
    protected $recipient_phone;

    public function setUp(): void
    {
        parent::setUp();
        $this->walletService = $this->app->make(WalletService::class);

        $this->sender = User::factory()->create();
        $this->sender_wallet = Wallet::factory()->create(['user_id' => $this->sender->id, 'balance' => 1000]);

        $this->recipient_phone = 123456789;
        $this->recipient = User::factory()->create(['phone' => $this->recipient_phone]);
        $this->recipient_wallet = Wallet::factory()->create(['user_id' => $this->recipient->id, 'balance' => 0]);

        $this->actingAs($this->sender, 'user-api');
    }

    public function testGetAuthUserWallet()
    {
        $response = $this->getJson(route('api.v1.user.get.wallet'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['balance', 'transactions']]);
    }

    public function testFundWalletSuccessfully()
    {
        $amount = 100;

        $this->actingAs($this->sender, 'user-api');

        $response = $this->postJson(route('api.v1.user.fund.auth.wallet'), ['amount' => $amount]);
        $response->assertStatus(200);
        $response->assertJson(['message' => __('main.fund_wallet_successfully')]);

        $this->assertDatabaseHas('wallets', [
            'user_id' => $this->sender->id,
            'balance' => $this->sender_wallet->balance + $amount,
        ]);
    }

    public function testTransferMoneySuccessfully()
    {
        $this->actingAs($this->sender, 'user-api');

        $amount = 50;

        $fee = $this->walletService->calculateTransferFee($amount);
        $total_amount_after_fee = $amount + $fee;

        $this->assertTrue($this->sender_wallet->balance >= $total_amount_after_fee, 'Sender has insufficient balance');

        $response = $this->postJson(route('api.v1.user.wallet.transfer.from.account.to.account'), [
            'amount' => $amount,
            'recipient_phone' => $this->recipient_phone,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => __('main.transfer_money_successfully')]);

        $this->assertDatabaseHas('wallets', ['user_id' => $this->sender->id, 'balance' => $this->sender_wallet->balance - $total_amount_after_fee]);
        $this->assertDatabaseHas('wallets', ['user_id' => $this->recipient->id, 'balance' => $amount]);
    }
}
