<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permissions;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            // Permissions::create(
            //     [
            //         'created_at' => \Carbon\Carbon::now(),
            //         'updated_at' => \Carbon\Carbon::now(),
            //         'route_name' => 'Dashboard',
            //         'url' => 'dashboard',
            //         'permission_name' => 'Dashboard',
            //     ]);
            Permissions::create(
                [
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'route_name' => 'Gender',
                    'url' => 'list-gender',
                    'permission_name' => 'Gender',
                ]);
            Permissions::create(
                [
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'route_name' => 'Marital Status',
                    'url' => 'list-maritalstatus',
                    'permission_name' => 'Marital Status',
                ]);
                Permissions::create(
                [
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'route_name' => 'Relations',
                    'url' => 'list-relation',
                    'permission_name' => 'Relations',
                ]);
                Permissions::create(
                [
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'route_name' => 'List Skills',
                    'url' => 'list-skills',
                    'permission_name' => 'List Skills',
                ]);

            Permissions::create(
                [
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'route_name' => 'Role',
                    'url' => 'list-role',
                    'permission_name' => 'Role',
                ]);
            Permissions::create(
                [
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'route_name' => 'Registarion Status',
                    'url' => 'list-registrationstatus',
                    'permission_name' => 'Registarion Status',
                ]);
            Permissions::create(
                [
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'route_name' => 'Document Types',
                    'url' => 'list-documenttype',
                    'permission_name' => 'Document Types',
                ]);
            Permissions::create(
                [
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'route_name' => 'Users Managment',
                    'url' => 'list-users',
                    'permission_name' => 'Users Managment',
                ]);
                Permissions::create(
                    [
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now(),
                        'route_name' => 'Projects Managment',
                        'url' => 'list-projects',
                        'permission_name' => 'Projects Managment',
                    ]);            
    }
}
