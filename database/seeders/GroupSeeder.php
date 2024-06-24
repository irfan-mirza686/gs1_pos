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
                'module_name' => 'User Management',
                'module_page' => 'user_management'
            ],

            [
                'id' => 2,
                'module_name' => 'User Management > Users',
                'module_page' => 'users'
            ],
            [
                'id' => 3,
                'module_name' => 'User Management > Roles',
                'module_page' => 'roles'
            ],
            [
                'id' => 4,
                'module_name' => 'Inventory',
                'module_page' => 'inventory'
            ],
            [
                'id' => 5,
                'module_name' => 'Inventory > Products',
                'module_page' => 'products'
            ],
            [
                'id' => 6,
                'module_name' => 'Inventory > Expired Products',
                'module_page' => 'expired_products'
            ],

            [
                'id' => 7,
                'module_name' => 'Inventory > Categories',
                'module_page' => 'categiries'
            ],

            [
                'id' => 8,
                'module_name' => 'Inventory > Brands',
                'module_page' => 'brands'
            ],
            [
                'id' => 9,
                'module_name' => 'Inventory > Units',
                'module_page' => 'units'
            ],
            [
                'id' => 10,
                'module_name' => 'Inventory > Print Barcodes',
                'module_page' => 'print_barcodes'
            ],
            [
                'id' => 11,
                'module_name' => 'Inventory > Print Qrcodes',
                'module_page' => 'print_qrcodes'
            ],
            [
                'id' => 12,
                'module_name' => 'Stock Management',
                'module_page' => 'stock_management'
            ],
            [
                'id' => 13,
                'module_name' => 'Stock Management > Manage Stock',
                'module_page' => 'manage_stock'
            ],
            [
                'id' => 14,
                'module_name' => 'Stock Management > Stock Adjustment',
                'module_page' => 'stock_adjustment'
            ],
            [
                'id' => 15,
                'module_name' => 'Stock Management > Stock Transfer',
                'module_page' => 'stock_transfer'
            ],
            [
                'id' => 16,
                'module_name' => 'Sales Management',
                'module_page' => 'sales_management'
            ],
            [
                'id' => 17,
                'module_name' => 'Sales Management > Sales',
                'module_page' => 'sales'
            ],
            [
                'id' => 18,
                'module_name' => 'Sales Management > Sales Returns',
                'module_page' => 'sales_returns'
            ],
            [
                'id' => 19,
                'module_name' => 'Sales Management > Pos',
                'module_page' => 'pos'
            ],
            [
                'id' => 20,
                'module_name' => 'Sales Management > Customers',
                'module_page' => 'customers'
            ],
            [
                'id' => 21,
                'module_name' => 'Settings',
                'module_page' => 'settings'
            ],
            [
                'id' => 22,
                'module_name' => 'Reports',
                'module_page' => 'reports'
            ],
            [
                'id' => 23,
                'module_name' => 'Reports > Sales Report',
                'module_page' => 'sales_report'
            ],
            [
                'id' => 24,
                'module_name' => 'Reports > Inventory Report',
                'module_page' => 'inventory_report'
            ],
            [
                'id' => 25,
                'module_name' => 'Reports > Customer Report',
                'module_page' => 'customer_report'
            ],
            [
                'id' => 26,
                'module_name' => 'Reports > Tax Report',
                'module_page' => 'tax_report'
            ],
            [
                'id' => 27,
                'module_name' => 'Reports > Profit & loss Report',
                'module_page' => 'profile_loss_report'
            ],
            [
                'id' => 28,
                'module_name' => 'Dashboard',
                'module_page' => 'dashboard'
            ]

        ];
        GroupModule::insert($modules);
    }
}
