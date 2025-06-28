<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    

  public function run()
{
    // Reset cached permissions
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    // Create permissions
    $permissions = [
        'view_posts',
        'create_posts',
        'edit_own_posts',
        'delete_own_posts',
        'manage_all_posts',
        'manage_users',
        'manage_settings'
    ];

    foreach ($permissions as $permission) {
        Permission::findOrCreate($permission);
    }

    // Create roles
    $admin = Role::findOrCreate('admin');
    $admin->syncPermissions(Permission::all());

    $user = Role::findOrCreate('user');
    $user->syncPermissions([
        'view_posts',
        'create_posts',
        'edit_own_posts',
        'delete_own_posts'
    ]);
}

}
