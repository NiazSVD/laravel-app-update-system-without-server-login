<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'dashboard view',

            'user view',
            'user create',
            'user edit',
            'user delete',

            'role view',
            'role create',
            'role edit',
            'role delete',
            'role permission',

            'order view',
            'order create',
            'order edit',
            'order delete',

            'cart view',
            'my orders',
            


            'setting update',
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }
    }
}
