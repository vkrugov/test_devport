<?php

namespace App\Http\Requests\Game;

use App\Rules\UserGameRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read $user_id
 */
class StoreRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'int',
                new UserGameRule(),
            ],
        ];
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }
}
