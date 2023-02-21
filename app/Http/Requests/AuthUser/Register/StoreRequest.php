<?php

namespace App\Http\Requests\AuthUser\Register;

use App\Rules\PhoneRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $name
 * @property-read string $phone
 */
class StoreRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:45|unique:users,name',
            'phone' => [
                'required',
                'string',
                'max:45',
                'unique:users,phone',
                new PhoneRule(),
            ],
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }
}
