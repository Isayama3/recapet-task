<?php

namespace Tests\Feature\Wallet;

use App\Models\User;
use App\Models\Wallet;
use App\Repositories\WalletRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $walletRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->walletRepository = $this->app->make(WalletRepository::class);
    }

    public function testGetUserWalletWithRelations()
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $retrievedWallet = $this->walletRepository->getUserWallet($user->id, ['transactions']);
        $this->assertEquals($wallet->id, $retrievedWallet->id);
        $this->assertNotNull($retrievedWallet->transactions);
    }
}
