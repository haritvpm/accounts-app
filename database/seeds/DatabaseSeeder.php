<?php

use Illuminate\Database\Seeder;
use App\Models\Year;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,

        ]);


         $years = [
            [
                'id'             => 1,
                'financial_year'  => '2019-20',
                'active'          => '0',
                
            ],
            [
                'id'             => 2,
                'financial_year' => '2020-21',
                'active'          => '1',
                
            ],
            [
                'id'             => 3,
                'financial_year'  => '2021-22',
                'active'          => '1',
                
            ],

        ];

        Year::insert($years);


        



    }
}
