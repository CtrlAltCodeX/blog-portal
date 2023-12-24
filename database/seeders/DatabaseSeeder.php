<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $writerRole = Role::create(['name' => 'Writer']);

        Permission::create(['name' => 'Post access']);
        Permission::create(['name' => 'Post edit']);
        Permission::create(['name' => 'Post create']);
        Permission::create(['name' => 'Post delete']);

        Permission::create(['name' => 'Role access']);
        Permission::create(['name' => 'Role edit']);
        Permission::create(['name' => 'Role create']);
        Permission::create(['name' => 'Role delete']);

        Permission::create(['name' => 'User access']);
        Permission::create(['name' => 'User edit']);
        Permission::create(['name' => 'User create']);
        Permission::create(['name' => 'User delete']);

        Permission::create(['name' => 'Permission access']);
        Permission::create(['name' => 'Permission edit']);
        Permission::create(['name' => 'Permission create']);
        Permission::create(['name' => 'Permission delete']);

        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('admin123'),
        ])->assignRole($adminRole);

        $adminRole->givePermissionTo(Permission::all());

        User::create([
            'name'     => 'Writer',
            'email'    => 'writer@example.com',
            'password' => bcrypt('admin123'),
        ])->assignRole($writerRole);

        $writerRole->givePermissionTo(['User create', 'User access']);

    }
}
