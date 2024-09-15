<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModelHasRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()->get();

        foreach ($users as $user) {
            $user->username == 'admin' ? $user->syncRoles('admin') : $user->syncRoles('pharmacist');
        }
    }
}
