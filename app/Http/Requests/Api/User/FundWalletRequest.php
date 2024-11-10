<?php

namespace App\Http\Requests\Api\User;

use App\Base\Request\Api\UserBaseRequest;

class FundWalletRequest extends UserBaseRequest
{
    public function rules(): array
    {
        return [
            'amount' =>'required|numeric|min:0.01',
        ];
    }
}
