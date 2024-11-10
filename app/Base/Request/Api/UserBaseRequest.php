<?php

namespace App\Base\Request\Api;

use Illuminate\Support\Facades\Auth;
use App\Base\Traits\Response\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserBaseRequest extends FormRequest
{
    use ApiResponseTrait;

    public function authorize()
    {
        if (app()->runningInConsole()) {
            return true;
        }

        return Auth::guard('user-api')->check();
    }

    public function failedValidation(Validator $validator)
    {
        $errors = [];
        foreach ($validator->errors()->toArray() as $key => $error) {
            $errors[$key] = $error[0];
        }

        throw new HttpResponseException($this->respondWithErrors(
            message: $errors[array_key_first($errors)],
            errors: $errors
        ));
    }
}
