<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'dolly@katuiscia.com'],
            [
                'firstname' => 'Dolly',
                'lastname'  => 'Katuiscia',
                'name'      => 'Dolly Katuiscia',
                'password'  => Hash::make('Million2026'),
                'is_admin'  => true,
                'is_active' => true,
                'country'   => 'FR',
            ]
        );

        $this->command->info('Admin user created: dolly@katuiscia.com');
    }
}
