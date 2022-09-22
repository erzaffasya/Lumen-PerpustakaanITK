<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123123123'),
            'role' => 'Admin',
        ]);
        User::create([
            'name' => 'dosen',
            'email' => 'dosen@gmail.com',
            'password' => bcrypt('123123123'),
            'role' => 'Dosen',
        ]);
        User::create([
            'name' => 'user',
            'email' => 'member@gmail.com',
            'password' => bcrypt('123123123'),
            'role' => 'Mahasiswa',
        ]);
    }
}
