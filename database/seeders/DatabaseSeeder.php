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
        Permission::create(['name' => 'User approved']);
        Permission::create(['name' => 'User edit']);
        Permission::create(['name' => 'User create']);
        Permission::create(['name' => 'User delete']);

        Permission::create(['name' => 'Permission access']);
        Permission::create(['name' => 'Permission edit']);
        Permission::create(['name' => 'Permission create']);
        Permission::create(['name' => 'Permission delete']);


        Permission::create(['name' => 'Listing edit']);
        Permission::create(['name' => 'Listing access']);
        Permission::create(['name' => 'Listing create']);
        Permission::create(['name' => 'Listing delete']);
        Permission::create(['name' => 'Listing publish']);

        
        Permission::create(['name' => 'Inventory edit']);
        Permission::create(['name' => 'Inventory access']);
        Permission::create(['name' => 'Inventory create']);
        Permission::create(['name' => 'Inventory delete']);
        
        Permission::create(['name' => 'Site Access']);
        Permission::create(['name' => 'Site Update']);

        Permission::create(['name' => 'Configure Blog']);
        Permission::create(['name' => 'Configure Update']);
        
        
        Permission::create(['name' => 'Dashboard Access']);

        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'status'   => 1,
            'mobile'   => '123456789',
            'account_type' => 1,
            'aadhaar_no' => '545454654',
            'father_name' => 'Jhon Doe',
            'mother_name' => 'Jenny',
            'state' => 'test',
            'pincode' => '201009',
            'full_address' => 'Test address',
            'plain_password' => 'admin123',
        ])->assignRole($adminRole)->assignRole($writerRole);

        $adminRole->givePermissionTo(Permission::all());

        User::create([
            'name'     => 'Writer',
            'email'    => 'writer@example.com',
            'password' => bcrypt('admin123'),
            'status'   => 1,
            'mobile'   => '123456789',
            'account_type' => 1,
            'aadhaar_no' => '545454654',
            'father_name' => 'Jhon Doe',
            'mother_name' => 'Jenny',
            'state' => 'test',
            'pincode' => '201009',
            'full_address' => 'Test address',
            'plain_password' => 'admin123',
        ])->assignRole($writerRole);

        $writerRole->givePermissionTo(['User create', 'User access']);
    }
}
