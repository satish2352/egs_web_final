<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(
            [
                'email' => 'admin@gmail.com',
                // 'u_uname' => 'admin@gmail.com',
                'password' => bcrypt('admin@gmail.com'),
                'role_id' => 1,
                'f_name' => 'fname',
                'm_name' => 'mname',
                'l_name' => 'lname',
                'number' => 'number',
                'designation' => 'designation',
                'address' => 'address',
                'state' => 'state',
                'city' => 'city',
                'pincode' => 'pincode',
                'ip_address' => '192.168.1.32',
            ]);
            
        User::create(
        [
            'email' => 'test@gmail.com',
            // 'u_uname' => 'test@gmail.com',
            'password' => bcrypt('test@gmail.com'),
            'role_id' => 1,
            'f_name' => 'fname',
            'm_name' => 'mname',
            'l_name' => 'lname',
            'number' => 'number',
            'designation' => 'designation',
            'address' => 'address',
            'state' => 'state',
            'city' => 'city',
            'pincode' => 'pincode',
            'ip_address' => '192.168.1.32',
        ]);

        
    }
}