<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'RoleID'=>'R001',
                'RoleName' => 'Admin',
            ],
        
            [
                'RoleID'=>'R002',
                'RoleName'=>'User',
            ]
        ]);
    }
}
