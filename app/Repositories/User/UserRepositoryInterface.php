<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    /**
     * @param array $attributes
     *
     * @return bool
     */
    public function isExists(array $attributes): bool;

    /**
     * @param int $userId
     *
     * @return \App\Models\User
     */
    public function loadUserById(int $userId): object;

    /**
     * @param array $attributes
     *
     * @return \App\Models\User
     */
    public function create(array $attributes): User;

    /**
     * @param \App\Models\User $user
     * @param array            $attributes
     *
     * @return \App\Models\User
     */
    public function update(User $user, array $attributes): User;

    /**
     * @param int|null    $limit
     * @param int|null    $offset
     * @param array       $filters
     * @param string|null $orderBy
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function loadAll(int $limit = null,
                            ?int $offset = null,
                            array $filters = [],
                            ?string $orderBy = null): LengthAwarePaginator;
}
