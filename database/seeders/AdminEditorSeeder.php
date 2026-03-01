<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminEditorSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_premium' => true,
                'email_verified_at' => now(),
            ]
        );

        // Editor
        User::updateOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('editor123'),
                'role' => 'editor',
                'is_premium' => false,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✓ Admin (admin@example.com / admin123) ve Editor (editor@example.com / editor123) oluşturuldu.');
    }
}
