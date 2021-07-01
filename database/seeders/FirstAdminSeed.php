<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class FirstAdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            [
                'name' => 'Super-Admin',
                'email' => 'super-admin@lotto.com',
                'password' => bcrypt('lotto2021'),
                'role' => 'super-admin',
                'admin_id' => '0',
                'status' => '1'
            ],
        ];
        User::Truncate();
        foreach($datas as $data){
          $admin = User::create($data);
          $admin->assignRole([$data['role']]);
        }

    }
}
