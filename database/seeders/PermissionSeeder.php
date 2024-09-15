<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contexts = [
            'MasterMedicine',
            'MasterPatient',
            'TransactionMedicine',
            'TransactionPatient'
        ];

        $accesses = [
            'create',
            'read',
            'update',
            'delete'
        ];

        foreach ($contexts as $context) {
            foreach ($accesses as $access) {
                Permission::query()
                    ->create([
                        'name' => $context.'.'.$access,
                        'guard_name' => 'web'
                    ]);
            }
        }
    }
}
