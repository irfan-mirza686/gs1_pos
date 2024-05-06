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
        $settings = [
            'themeMode' => 'lightmode',
            'headerColor' => '',
            'sidebarColor' => '',
        ];
        $admin = [
            'name' => 'Admin',
            'group_id' => 1,
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456'),
            'code' => '123456',
            'settings' => $settings
        ];
        User::create($admin);
    }
}
