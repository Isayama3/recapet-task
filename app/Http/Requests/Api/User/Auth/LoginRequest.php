<?php

namespace App\Http\Requests\Api\User\Auth;

use App\Base\Request\Api\BaseRequest;

class LoginRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|max:20',
        ];
    }
}
