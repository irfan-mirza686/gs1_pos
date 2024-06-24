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
                'module_name' => 'User Management',
                'module_page' => 'user_management'
            ],

            [
                'module_name' => 'User Management > Users',
                'module_page' => 'users'
            ],
            [
                'module_name' => 'User Management > Roles',
                'module_page' => 'roles'
            ],
            [
                'module_name' => 'Inventory',
                'module_page' => 'inventory'
            ],
            [
                'module_name' => 'Inventory > Products',
                'module_page' => 'products'
            ],
            [
                'module_name' => 'Inventory > Expired Products',
                'module_page' => 'expired_products'
            ],

            [
                'module_name' => 'Inventory > Categories',
                'module_page' => 'categiries'
            ],

            [
                'module_name' => 'Inventory > Brands',
                'module_page' => 'brands'
            ],
            [
                'module_name' => 'Inventory > Units',
                'module_page' => 'units'
            ],
            [
                'module_name' => 'Inventory > Print Barcodes',
                'module_page' => 'print_barcodes'
            ],
            [
                'module_name' => 'Inventory > Print Qrcodes',
                'module_page' => 'print_qrcodes'
            ],
            [
                'module_name' => 'Stock Management',
                'module_page' => 'stock_management'
            ],
            [
                'module_name' => 'Stock Management > Manage Stock',
                'module_page' => 'manage_stock'
            ],
            [
                'module_name' => 'Stock Management > Stock Adjustment',
                'module_page' => 'stock_adjustment'
            ],
            [
                'module_name' => 'Stock Management > Stock Transfer',
                'module_page' => 'stock_transfer'
            ],
            [
                'module_name' => 'Sales Management',
                'module_page' => 'sales_management'
            ],
            [
                'module_name' => 'Sales Management > Sales',
                'module_page' => 'sales'
            ],
            [
                'module_name' => 'Sales Management > Sales Returns',
                'module_page' => 'sales_returns'
            ],
            [
                'module_name' => 'Sales Management > Pos',
                'module_page' => 'pos'
            ],
            [
                'module_name' => 'Sales Management > Customers',
                'module_page' => 'customers'
            ],
            [
                'module_name' => 'Settings',
                'module_page' => 'settings'
            ],
            [
                'module_name' => 'Reports',
                'module_page' => 'reports'
            ],
            [
                'module_name' => 'Reports > Sales Report',
                'module_page' => 'sales_report'
            ],
            [
                'module_name' => 'Reports > Inventory Report',
                'module_page' => 'inventory_report'
            ],
            [
                'module_name' => 'Reports > Customer Report',
                'module_page' => 'customer_report'
            ],
            [
                'module_name' => 'Reports > Tax Report',
                'module_page' => 'tax_report'
            ],
            [
                'module_name' => 'Reports > Profit & loss Report',
                'module_page' => 'profile_loss_report'
            ],
            [
                'module_name' => 'Dashboard',
                'module_page' => 'dashboard'
            ]

        ];
        GroupModule::insert($modules);
    }
}
