<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        // Delete table daeta before overriding.
        Role::query()->delete();
        Permission::query()->delete();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();


        $adminRole = Role::create(['name' => 'Admin']);
        $writerRole = Role::create(['name' => 'Writer']);

        Permission::create(['name' => 'Role create']);

        Permission::create(['name' => 'User Details (Main Menu)']);
        Permission::create(['name' => 'User approved']);
        Permission::create(['name' => 'User edit']);
        Permission::create(['name' => 'User create']);
        Permission::create(['name' => 'User delete']);

        Permission::create(['name' => 'Roles & Permissions (Main Menu)']);
        Permission::create(['name' => 'Permission edit']);
        Permission::create(['name' => 'Permission create']);
        Permission::create(['name' => 'Permission delete']);

        Permission::create(['name' => 'Listing (Main Menu)']);
        Permission::create(['name' => 'Listing edit']);
        Permission::create(['name' => 'Listing create']);
        Permission::create(['name' => 'Listing delete']);
        Permission::create(['name' => 'Listing publish']);

        Permission::create(['name' => 'Inventory (Main Menu)']);

        
        Permission::create(['name' => 'Inventory -> Manage Inventory']);
        
        Permission::create(['name' => 'Site Access']);
        Permission::create(['name' => 'Site Update']);

        Permission::create(['name' => 'Configure Blog']);
        Permission::create(['name' => 'Configure Update']);
        
        
        Permission::create(['name' => 'Dashboard']);

        User::find(1)->assignRole($adminRole)->assignRole($writerRole);

        $adminRole->givePermissionTo(Permission::all());
    }
}
