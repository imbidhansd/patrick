<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\User;

class UserTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $arr = [
            [
                'first_name' => 'Tony',
                'last_name' => 'Thomas',
                'user_type' => 'admin',
                'email' => 'admin@map.com',
                'password' => bcrypt('0987654321'),
                'status' => 'active',
            ],
        ];

        User::insert($arr);
    }
}
