<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Librova',
            'email' => 'admin@librova.id',
            'password' => bcrypt('renocraft87'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}