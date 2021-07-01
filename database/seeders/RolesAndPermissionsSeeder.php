<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Permission as m_permission;
use App\Models\RoleHasPermission as m_role_has_permission;
use App\Models\Role as m_role;
use App\Models\ModelHasPermission;
use App\Models\ModelHasRole;
use DB;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        m_role::Truncate();
        m_permission::Truncate();
        ModelHasPermission::Truncate();
        ModelHasRole::Truncate();
        m_role_has_permission::Truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'super-admin']);
        Permission::create(['name' => 'admin']);

        // create roles and assign created permissions

        // this can be done as separate statements

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(['super-admin', 'admin']);
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(['admin']);


        // or may be done by chaining
        // $role = Role::create(['name' => 'moderator'])
        //     ->givePermissionTo(['publish articles', 'unpublish articles']);
    }
}
