<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'users.index',
            'users.show',
            'users.store',
            'users.update',
            'users.destroy'
        ];
        //Permission list
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        //Admin
        $admin = Role::create(['name' => 'Administrador']);

        $admin->givePermissionTo($permissions);

        //Guest
        $guest = Role::create(['name' => 'Auditor']);

        $guest->givePermissionTo([
            $permissions[0],
            $permissions[1]
        ]);

        //User Admin
        $user = User::find(1); //Usuario Administrador
        $user->assignRole('Administrador');

        //Audit Admin
        $user = User::find(2); //Usuario Auditplay
        $user->assignRole('Auditor');
    }
}
