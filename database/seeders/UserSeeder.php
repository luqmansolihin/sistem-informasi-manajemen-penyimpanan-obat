<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()
            ->create([
                'name' => 'Admin',
                'username' => 'admin',
                'password' => bcrypt('admin*123#')
            ]);

        User::query()
            ->create([
                'name' => 'Pharmacist',
                'username' => 'pharmacist',
                'password' => bcrypt('pharmacist*123#')
            ]);
    }
}
