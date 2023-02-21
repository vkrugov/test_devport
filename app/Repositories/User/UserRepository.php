<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as DbBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class UserRepository implements UserRepositoryInterface
{
    private const DEFAULT_OFFSET = 0;
    private const DEFAULT_LIMIT  = 10;

    /**
     * @param array $attributes
     *
     * @return bool
     */
    public function isExists(array $attributes): bool
    {
        return User::where($attributes)->exists();
    }

    /**
     * @param int $userId
     *
     * @return object
     */
    public function loadUserById(int $userId): object
    {
        $user = $this->getUserDataByIds([$userId])-> first();

        if ($user === null) {
            throw new NotFoundResourceException();
        }

        return $user;
    }

    /**
     * @param array $attributes
     *
     * @return \App\Models\User
     */
    public function create(array $attributes): User
    {
        $user = new User();
        $user->name = $attributes['name'] ?? null;
        $user->phone = $attributes['phone'] ?? null;
        $user->email = $attributes['email'] ?? null;
        $user->save();

        return $user;
    }

    /**
     * @param \App\Models\User $user
     * @param array            $attributes
     *
     * @return \App\Models\User
     */
    public function update(User $user, array $attributes): User
    {
        if (array_key_exists('name', $attributes)) {
            $user->name = $attributes['name'];
        }
        if (array_key_exists('phone', $attributes)) {
            $user->phone = $attributes['phone'];
        }
        if (array_key_exists('email', $attributes)) {
            $user->email = $attributes['email'];
        }

        $user->save();

        return $user;
    }

    /**
     * @param int|null    $limit
     * @param int|null    $offset
     * @param array       $filters
     * @param string|null $orderBy
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function loadAll(?int $limit = null,
                            ?int $offset = null,
                            array $filters = [],
                            ?string $orderBy = null): LengthAwarePaginator
    {
        $limit = $limit ?? self::DEFAULT_LIMIT;
        $offset = $offset ?? self::DEFAULT_OFFSET;
        $page = ($offset + $limit) / $limit;

        $query = User::query()->select('id');

        if (count($filters) > 0) {
            $query = $this->applyFilters($query, $filters);
        }

        $query = $this->applyBasicSort($query, $orderBy);
        $total = $query->count();

        $userIds = $query
            ->groupBy('id')
            ->offset($offset)
            ->limit($limit)
            ->pluck('id')
            ->all();

        $userData = $this->getUserDataByIds($userIds, $orderBy);

        return new LengthAwarePaginator($userData, $total, $limit, $page);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null                           $orderBy
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyBasicSort(Builder $query, ?string $orderBy): Builder
    {
        $column = str_replace('-', '', $orderBy);
        $direction = str_starts_with($orderBy, '-') ? 'desc' : 'asc';

        switch ($orderBy) {
            case 'name':
            case 'phone':
            case 'created_at':
                $query->orderBy($column, $direction);
                break;
            case 'games_count':
                $query->leftJoin('games', 'game.user_id', 'users.id');
                $query->orderByRaw("COUNT(games.id) {$direction}");
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query;
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param string|null                        $orderBy
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function applyFinalSort(DbBuilder $query, ?string $orderBy): DbBuilder
    {
        $column = str_replace('-', '', $orderBy);
        $direction = str_starts_with($orderBy, '-') ? 'desc' : 'asc';

        switch ($orderBy) {
            case 'name':
            case 'phone':
            case 'created_at':
            case 'games_count':
                $query->orderBy($column, $direction);
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array                                 $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyFilters(Builder $query, array $filters): Builder
    {
        if (array_key_exists('searchInput', $filters)) {
            $searchInput = $filters['searchInput'];
            $query->where(function (Builder $query) use ($searchInput) {
                $query->where('name', $searchInput)->orWhere('phone', $searchInput);
            });
        }

        return $query;
    }

    /**
     * @param array       $userIds
     * @param string|null $orderBy
     *
     * @return \Illuminate\Support\Collection
     */
    private function getUserDataByIds(array $userIds, ?string $orderBy = null): Collection
    {
        $baseQuery = User::query()->whereIn('id', $userIds)->select([
            'id',
            'name',
            'phone',
            'email',
            'created_at',
            'updated_at',
        ]);

        $finalQuery = DB::table(DB::raw("({$this->getSql($baseQuery)}) AS `users`"))
                        ->selectRaw('users.*')
                        ->selectRaw('COUNT(games.id) as games_count')
                        ->leftJoin('games', 'games.user_id', 'users.id');

        $finalQuery = $this->applyFinalSort($finalQuery, $orderBy);
        $finalQuery->groupBy('users.id');
        $userData = $finalQuery->get();

        return $this->transformUserData($userData);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return string
     */
    public function getSql(Builder$builder): string
    {
        $sql = $builder->toSql();
        foreach ($builder->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }

        return $sql;
    }

    /**
     * @param \Illuminate\Support\Collection $userData
     *
     * @return \Illuminate\Support\Collection
     */
    private function transformUserData(Collection $userData): Collection
    {
        $userIds = $userData->pluck('id')->all();
        $roles = $this->loadRoles($userIds);

        return $userData->transform(function ($userItem) use ($roles) {
            $userItem->roles = $roles->get($userItem->id);

            return $userItem;
        });
    }

    /**
     * @param array $userIds
     *
     * @return \Illuminate\Support\Collection
     */
    private function loadRoles(array $userIds): Collection
    {
        return DB::table('model_has_roles')->select([
            'roles.name as name',
            'roles.id as id',
            'model_has_roles.model_id as user_id'
        ])->leftJoin('roles', 'roles.id', 'model_has_roles.role_id')
          ->whereIn('model_has_roles.model_id', $userIds)
          ->where('model_has_roles.model_type', User::class)
          ->get()
          ->keyBy('user_id');
    }
}
