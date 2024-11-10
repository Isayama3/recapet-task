<?php

namespace App\Http\Requests\Api\User;

use App\Base\Request\Api\UserBaseRequest;

class TransferMoneyRequest extends UserBaseRequest
{
    public function rules(): array
    {
        return [
            'amount' =>'required|numeric|min:0.01',
            'recipient_phone' => 'required|numeric|exists:users,phone'
        ];
    }
}
