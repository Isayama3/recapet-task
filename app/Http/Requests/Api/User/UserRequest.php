<?php

namespace App\Http\Requests\Api\User;

use App\Base\Request\Api\UserBaseRequest;

class UserRequest extends UserBaseRequest
{
    public function rules(): array
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                    return [];
                }
            case 'POST': {
                    return [
                    ];
                }
            case 'PUT': {
                    return [];
                }
        }
    }
}
