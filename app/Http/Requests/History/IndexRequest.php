<?php

namespace App\Http\Requests\History;

use App\Http\Requests\PaginationRequest;

/**
 * @property-read array $filters
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
}
