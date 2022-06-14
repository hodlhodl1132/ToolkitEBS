<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::create(['name' => 'pages.root.edit']);
        Permission::create(['name' => 'pages.root.delete']);
        Permission::create(['name' => 'pages.edit']);
        Permission::create(['name' => 'pages.delete']);
        Permission::create(['name' => 'admin.dashboard']);
        Permission::create(['name' => 'admin.dashboard.stream']);
        Permission::create(['name' => 'admin.dashboard.stream.edit']);
        Permission::create(['name' => 'admin.dashboard.livestats']);
        Permission::create(['name' => 'admin.users.view']);
        Permission::create(['name' => 'admin.users.edit']);

        $superAdminRole = Role::create(['name' => 'super_admin'])
            ->givePermissionTo(Permission::all());
        $adminRole = Role::create(['name' => 'admin'])
            ->givePermissionTo([
                'pages.edit',
                'admin.dashboard',
                'admin.dashboard.stream',
                'admin.dashboard.stream.edit',
                'admin.dashboard.livestats',
                'admin.users.view',
                'admin.users.edit'
            ]);
        $communityManagerRole = Role::create(['name' => 'community_manager'])
            ->givePermissionTo([
                'pages.edit',
                'admin.dashboard',
                'admin.dashboard.livestats',
                'admin.users.view'
            ]);

        $superAdmin = User::where('provider_id', "124055459")->first();
        if ($superAdmin != null) {
            $superAdmin->assignRole('super_admin');
        }
    }
}
