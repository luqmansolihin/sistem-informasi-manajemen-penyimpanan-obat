<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::query()->get();
        $permissions = Permission::query()->get();

        foreach ($roles as $role) {
            foreach ($permissions as $permission) {
                if (str_contains($permission->name, 'update') OR str_contains($permission->name, 'delete')) {
                    if ($role->name == 'admin')
                        $role->givePermissionTo($permission);
                } else {
                    $role->givePermissionTo($permission);
                }
            }
        }
    }
}
