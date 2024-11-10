<?php

namespace App\Base\Request\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AdminBaseRequest extends FormRequest
{
    public function authorize()
    {
        if (app()->runningInConsole()) {
            return true;
        }
        return true;
    }

    public function failedValidation(Validator $validator)
    {
        return redirect()
            ->back()
            ->withInput()
            ->withErrors($validator->errors());
    }
}
