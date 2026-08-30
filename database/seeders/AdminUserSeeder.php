<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@profilemiis.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin12345'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );
    }
}
