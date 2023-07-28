<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $writer_role = Role::create(['name' => 'writer']);
        $writer = User::create([
            'name' => "Writer",
            'email' => "writer@gmail.com",
            'role_id' => 1,
            'password' => bcrypt('password')
        ]);
        $permission = Permission::create(['name' => 'edit articles']);
        $writer->syncPermissions($permission);
        $writer->assignRole($writer_role->id);

        $admin_role = Role::create(['name' => 'admin']);
        $admin = User::create([
            'name' => "Admin",
            'email' => "Admin@gmail.com",
            'role_id' => 2,
            'password' => bcrypt('password')
        ]);
        $permission_admin_create = Permission::create(['name' => 'create articles']);
        $permission_admin_update = Permission::create(['name' => 'update articles']);
        $permission_admin_delete = Permission::create(['name' => 'delete articles']);
        $permission_admin_read = Permission::create(['name' => 'read articles']);
        $admin_role->givePermissionTo([
            $permission_admin_create,
            $permission_admin_read,
            $permission_admin_update,
            $permission_admin_delete
        ]);
        $admin->assignRole($admin_role->id);

        $user_role = Role::create(['name' => 'user']);
        $user = User::create([
            'name' => "User",
            'email' => "user@gmail.com",
            'role_id' => 3,
            'password' => bcrypt('password')
        ]);

        $permission_user = Permission::create(['name' => 'view post content']);
        $user_role->syncPermissions($permission_user);
        $user->assignRole($user_role->id);

        $user1 = User::create([
            'name' => "User1",
            'email' => "user1@gmail.com",
            'role_id' => 3,
            'password' => bcrypt('password')
        ]);
        $user_role->syncPermissions($permission_user);
        $user1->assignRole($user_role->id);
    }
}
