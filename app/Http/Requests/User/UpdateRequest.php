<?php

namespace App\Http\Requests\User;

use App\Rules\PhoneRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string|null $name
 * @property-read string|null $phone
 */
class UpdateRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => "sometimes|required|string|max:45|unique:users,name,{$this->user->id}",
            'phone' => [
                'sometimes',
                'required',
                'string',
                'max:45',
                "unique:users,phone,{$this->user->id}",
                new PhoneRule(),
            ],
        ];
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }
}
