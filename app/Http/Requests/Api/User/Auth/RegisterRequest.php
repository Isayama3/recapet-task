<?php

namespace App\Http\Requests\Api\User\Auth;

use App\Base\Request\Api\BaseRequest;

class RegisterRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'phone' => 'required|numeric|unique:users|max_digits:20',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
        ];
    }
}
