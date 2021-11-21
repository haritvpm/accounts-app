<?php

use App\Models\Permission;
use Illuminate\Database\Seeder;

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
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'head_create',
            ],
            [
                'id'    => 18,
                'title' => 'head_edit',
            ],
            [
                'id'    => 19,
                'title' => 'head_show',
            ],
            [
                'id'    => 20,
                'title' => 'head_delete',
            ],
            [
                'id'    => 21,
                'title' => 'head_access',
            ],
            [
                'id'    => 22,
                'title' => 'year_create',
            ],
            [
                'id'    => 23,
                'title' => 'year_edit',
            ],
            [
                'id'    => 24,
                'title' => 'year_show',
            ],
            [
                'id'    => 25,
                'title' => 'year_delete',
            ],
            [
                'id'    => 26,
                'title' => 'year_access',
            ],
            [
                'id'    => 27,
                'title' => 'allocation_create',
            ],
            [
                'id'    => 28,
                'title' => 'allocation_edit',
            ],
            [
                'id'    => 29,
                'title' => 'allocation_show',
            ],
            [
                'id'    => 30,
                'title' => 'allocation_delete',
            ],
            [
                'id'    => 31,
                'title' => 'allocation_access',
            ],
            [
                'id'    => 32,
                'title' => 'salary_bill_detail_create',
            ],
            [
                'id'    => 33,
                'title' => 'salary_bill_detail_edit',
            ],
            [
                'id'    => 34,
                'title' => 'salary_bill_detail_show',
            ],
            [
                'id'    => 35,
                'title' => 'salary_bill_detail_delete',
            ],
            [
                'id'    => 36,
                'title' => 'salary_bill_detail_access',
            ],
            [
                'id'    => 37,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
