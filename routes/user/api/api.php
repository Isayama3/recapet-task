<?php

use Illuminate\Support\Facades\Route;

// Note :: Never change name of the routes, it will break everything.

Route::group(['middleware' => ['auth:user-api']], function () {
    Route::post('logout', 'Auth\AuthController@logout')->name('logout');

    Route::get('wallet', 'Wallet\WalletController@getAuthUserWallet')->name('get.wallet');
    Route::post('wallet/fund', 'Wallet\WalletController@fundAuthUserWallet')->name('fund.auth.wallet');
    Route::post('wallet/transfer', 'Wallet\WalletController@transferMoney')->name('wallet.transfer.from.account.to.account');
    Route::resource('wallet/transactions', 'Wallet\WalletTransactionController')->only(['index', 'show']);
});
