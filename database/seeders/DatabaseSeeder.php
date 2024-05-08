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
        Permission::create(['name' => 'User Details -> All Users List -> Edit']);
        Permission::create(['name' => 'User create']);
        Permission::create(['name' => 'User delete']);
        Permission::create(['name' => 'User Details -> All Users List']);

        Permission::create(['name' => 'Roles & Permissions (Main Menu)']);
        Permission::create(['name' => 'Roles & Permissions -> Assign Permissions to Roles']);
        Permission::create(['name' => 'Roles & Permissions -> View All Roles & Permissions']);
        Permission::create(['name' => 'Roles & Permissions -> Assign Permissions to Roles -> Edit']);
        Permission::create(['name' => 'Roles & Permissions -> Assign Permissions to Roles -> Create']);
        Permission::create(['name' => 'Roles & Permissions -> Assign Permissions to Roles -> Delete']);

        Permission::create(['name' => 'Listing (Main Menu)']);
        Permission::create(['name' => 'Listing create']);
        Permission::create(['name' => 'Listing -> Search Listing -> Delete']);
        Permission::create(['name' => 'Listing -> Search Listing -> Edit']);
        Permission::create(['name' => 'Listing -> Search Listing']);
        Permission::create(['name' => 'Listing create ( DB )']);
        Permission::create(['name' => 'Pending Listing ( DB )']);
        Permission::create(['name' => 'Pending Listing ( DB ) -> Edit']);
        Permission::create(['name' => 'Pending Listing ( DB ) -> Delete']);
        Permission::create(['name' => 'Listing publish']);
        Permission::create(['name' => 'Pending Listing ( DB ) -> Reject ( DB )']);
        Permission::create(['name' => 'Pending Listing ( DB ) -> Update ( DB )']);
        Permission::create(['name' => 'Pending Listing ( DB ) -> Save as Draft']);
        Permission::create(['name' => 'Pending Listing ( DB ) -> Publish to Website']);

        Permission::create(['name' => 'Image Creation (Main Menu)']);
        Permission::create(['name' => 'Image Creation -> Single Image Maker']);
        Permission::create(['name' => 'Image Creation -> Combo Image Maker']);
        Permission::create(['name' => 'Image Creation -> Gallery ( DB )']);
        Permission::create(['name' => 'Image Creation -> Gallery ( DB ) -> Delete']);

        Permission::create(['name' => 'Inventory (Main Menu)']);
        Permission::create(['name' => 'Inventory -> Manage Inventory']);
        Permission::create(['name' => 'Inventory -> Drafted Inventory']);
        Permission::create(['name' => 'Inventory -> Under Review Inventory']);
        Permission::create(['name' => 'Inventory -> Listing Counts Report']);
        Permission::create(['name' => 'Inventory -> Manage Inventory -> Edit']);
        Permission::create(['name' => 'Inventory -> Manage Inventory -> Delete']);
        Permission::create(['name' => 'Inventory -> Counts Report']);
        Permission::create(['name' => 'Inventory -> Counts Report -> Delete']);
        
        Permission::create(['name' => 'Settings (Main Menu)']);
        Permission::create(['name' => 'Settings -> Site Access']);
        Permission::create(['name' => 'Settings -> Site Update']);
        Permission::create(['name' => 'Settings -> Configure Blog']);
        Permission::create(['name' => 'Settings -> Configure Blog Update']);
        Permission::create(['name' => 'Settings -> Backup E-Mail']);
        Permission::create(['name' => 'Settings -> Backup Logs & Links']);
        Permission::create(['name' => 'Settings -> Validations']);
        
        Permission::create(['name' => 'Dashboard']);

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
            "allow_sessions" => 0
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
            "allow_sessions" => 0
        ])->assignRole($writerRole);

        User::find(1)->assignRole($adminRole)->assignRole($writerRole);

        $adminRole->givePermissionTo(Permission::all());
    }
}
