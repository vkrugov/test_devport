<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Game\GameRepositoryInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => fake()->userName(),
            'phone' => '+380' . fake()->numerify('#########'),
            'email' => null,
        ];
    }
}
