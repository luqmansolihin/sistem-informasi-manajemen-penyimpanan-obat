<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()
            ->create([
                'name' => 'admin',
                'guard_name' => 'web'
            ]);

        Role::query()
            ->create([
                'name' => 'pharmacist',
                'guard_name' => 'web'
            ]);
    }
}
