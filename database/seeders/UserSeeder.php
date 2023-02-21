<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        /** @var \App\Models\User $user */
        $user = User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name'           => 'Admin',
            'password'       => bcrypt('123456'),
            'remember_token' => Str::random(10),
        ]);

        $user->setRole(Role::ADMIN);
    }
}
