<?php

namespace App\Rules;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class UserGameRule implements Rule
{
    /**
     * @var string
     */
    private string $message = 'User Id Is Invalid.';

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $user = User::find($value);

        if ($user === null) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
}
