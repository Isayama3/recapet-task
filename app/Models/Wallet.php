<?php

namespace App\Models;

use App\Base\Models\BaseModel;

class Wallet extends BaseModel
{
    protected $casts = [
        'balance' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class)
            ->where(function ($query) {
                $query->where('wallet_id', $this->id)
                    ->orWhere('recipient_wallet_id', $this->id);
            });
    }
}
