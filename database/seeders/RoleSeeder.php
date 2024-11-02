<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin =  Role::create(['name' => "admin"]);
        $customer = Role::create(['name' => "customer"]);
        $staff = Role::create(['name' => "staff"]);

        $view_products =  Permission::create(['name' => 'view products']);
        $add_products =  Permission::create(['name' => 'add products']);
        $edit_products = Permission::create(['name' => 'edit products']);
        $delete_products = Permission::create(['name' => 'delete products']);

        $view_orders =  Permission::create(['name' => 'view orders']);
        $add_orders = Permission::create(['name' => 'add orders']);
        $edit_orders = Permission::create(['name' => 'edit orders']);
        $delete_orders =   Permission::create(['name' => 'delete orders']);
        $cancel_orders =   Permission::create(['name' => 'cancel orders']);
        $change_delivery_status_orders =   Permission::create(['name' => 'change delivery status orders']);
        $settings =   Permission::create(['name' => 'settings']);

        $admin->givePermissionTo($view_products, $add_products, $edit_products, $delete_products);
        $admin->givePermissionTo([$view_orders, $add_orders, $edit_orders, $delete_orders]);
        $admin->givePermissionTo([$settings]);

        $admin->save();

        $customer->givePermissionTo([$view_products]);
        $customer->givePermissionTo([$add_orders]);
        $customer->givePermissionTo([$cancel_orders]);
        $customer->save();

        $staff->givePermissionTo([$view_orders, $change_delivery_status_orders]);
        $staff->save();
    }
}
