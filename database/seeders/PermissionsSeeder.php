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
        Permission::create(['name' => 'pages.edit']);
        Permission::create(['name' => 'users.edit']);

        $superAdminRole = Role::create(['name' => 'super_admin'])
            ->givePermissionTo(Permission::all());
        $adminRole = Role::create(['name' => 'admin'])
            ->givePermissionTo(['pages.edit']);

        $superAdmin = User::where('provider_id', "124055459")->first();
        if ($superAdmin != null) {
            $superAdmin->assignRole('super_admin');
        }
    }
}
