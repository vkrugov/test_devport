<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Game\GameRepositoryInterface;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::factory()->count(35)->make()->each(function ($user) {
            $user->save();
            $user->setRole(Role::CUSTOMER);
            $randomGames = rand(1, 10);
            for($i = 0; $i <= $randomGames; $i ++) {
                app(GameRepositoryInterface::class)->createNew($user->id);
            }
        });
    }
}
