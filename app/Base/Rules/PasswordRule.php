<?php

namespace App\Base\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class PasswordRule implements ValidationRule
{

    public function validate($attribute, $value, $fail): void
    {
        if (!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/', $value)) {
            $fail('يجب ان تحتوى كلمة السر على حروف و ارقام و علامات والا تقل عن ٨ خانات');
        }
    }
}
