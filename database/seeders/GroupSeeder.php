<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GroupModule;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = [
            [
                'id' => 1,
                'module_name' => 'Staff Members',
                'module_page' => 'staff_members'
            ],
            [
                'id' => 2,
                'module_name' => 'Roles',
                'module_page' => 'roles'
            ],
            [
                'id' => 3,
                'module_name' => 'Products',
                'module_page' => 'products'
            ],
            [
                'id' => 4,
                'module_name' => 'Units',
                'module_page' => 'units'
            ],
            [
                'id' => 5,
                'module_name' => 'Sales',
                'module_page' => 'sales'
            ],
            [
                'id' => 6,
                'module_name' => 'Customers',
                'module_page' => 'customers'
            ],
            [
                'id' => 7,
                'module_name' => 'Dashboard',
                'module_page' => 'dashboard'
            ]

        ];
        GroupModule::insert($modules);
    }
}
