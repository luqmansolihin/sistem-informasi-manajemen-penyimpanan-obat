<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AddEmailToUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::orderBy('id')->get();

        foreach ($users as $user) {
            if ($user->username == 'admin') {
                $user->update(['email' => 'admin@gmail.com']); // Change with email of admin
            } else {
                $user->update(['email' => 'pharmacist@gmail.com']); // Change with email of pharmacist
            }
        }
    }
}
