<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
            ],
            [
                'id' => 2,
                'name' => 'A1',
                'email' => 'a1@kla.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
            ],

            [
                'id' => 3,
                'name' => 'C1',
                'email' => 'c1@kla.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
            ],

            [
                'id' => 4,
                'name' => 'A2',
                'email' => 'a2@kla.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
            ],

            [
                'id' => 5,
                'name' => 'A3',
                'email' => 'a3@kla.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
            ],

            [
                'id' => 6,
                'name' => 'B1',
                'email' => 'b1@kla.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
            ],

            [
                'id' => 7,
                'name' => 'B2',
                'email' => 'b2@kla.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
            ],
            [
                'id' => 8,
                'name' => 'B3',
                'email' => 'b3@kla.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
            ],

            [
                'id' => 9,
                'name' => 'C2',
                'email' => 'c2@kla.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
            ],
            [
                'id' => 10,
                'name' => 'C3',
                'email' => 'c3@kla.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
            ],

            [
                'id' => 11,
                'name' => 'E1',
                'email' => 'e1@kla.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
            ],
            [
                'id' => 12,
                'name' => 'E2',
                'email' => 'e2@kla.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
            ],
            [
                'id' => 13,
                'name' => 'A4',
                'email' => 'a4@kla.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
            ],
            [
                'id' => 14,
                'name' => 'C4',
                'email' => 'c4@kla.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
            ],

        ];

        User::insert($users);
    }
}
