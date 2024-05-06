<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class MatchOldPassword implements Rule
{
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        return Hash::check($value, authUser()->password);
    }

    public function message()
    {
        return 'Wrong data.';
    }
}
