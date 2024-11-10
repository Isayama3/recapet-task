<?php

namespace App\Models;

use App\Base\Models\BaseModel;

class WalletTransaction extends BaseModel
{
    protected $casts = [
        'amount' => 'float',
    ];
    
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
}
