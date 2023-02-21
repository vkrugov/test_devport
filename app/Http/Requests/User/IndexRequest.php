<?php

namespace App\Http\Requests\User;

use App\Http\Requests\PaginationRequest;

/**
 * @property-read array $filters
 * @property-read string|null $sort
 */
class IndexRequest extends PaginationRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        $parentRules = parent::rules();

        $rules = [
            'filters' => 'array',
            'sort'    => 'string|nullable',
        ];

        return array_merge($rules, $parentRules);
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters ?? [];
    }

    /**
     * @return string|null
     */
    public function getSort(): ?string
    {
        return $this->sort;
    }
}
