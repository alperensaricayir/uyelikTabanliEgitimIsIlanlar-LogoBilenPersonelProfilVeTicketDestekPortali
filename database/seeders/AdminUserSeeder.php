<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@portal.local'],
            [
                'name' => 'Platform Admin',
                'password' => bcrypt('admin-password-change-me'),
                'role' => 'admin',
                'is_premium' => true,
            ]
        );

        $this->command->info('✓ Admin kullanıcı oluşturuldu: admin@portal.local');
    }
}
