<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole    = Role::firstOrCreate(['name' => 'admin']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $vendorRole   = Role::firstOrCreate(['name' => 'vendor']);
        $deliveryRole = Role::firstOrCreate(['name' => 'delivery']);

        $adminRole->givePermissionTo(Permission::all());

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Md Habibur Rahman',
                'password' => Hash::make('12345678'),
            ]
        );
        $employeeUser = User::firstOrCreate(
            ['email' => 'employee@gmail.com'],
            [
                'name'     => 'Md Kamrulzaman ',
                'password' => Hash::make('12345678'),
            ]
        );
        $vendorUser = User::firstOrCreate(
            ['email' => 'vendor@gmail.com'],
            [
                'name'     => 'Md Niaz Khan',
                'password' => Hash::make('12345678'),
            ]
        );
        $deliveryUser = User::firstOrCreate(
            ['email' => 'delivery@gmail.com'],
            [
                'name'     => 'Md Mahamudul Khan',
                'password' => Hash::make('12345678'),
            ]
        );

        $adminUser->assignRole('admin');
        $employeeUser->assignRole('employee');
        $vendorUser->assignRole('vendor');
        $deliveryUser->assignRole('delivery');
    }
}
