<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'UserID' => 'U001',
                'Email' => 'admin@gmail.com',
                'FullName' => 'Nguyen Van Admin',
                'PasswordHash' => Hash::make('Admin@123'),
                'RoleID' => 'R001',
                'Phone' => '0901000001',
                'Status' => true,
                'ProfilePicture' => 'admin.jpg',
            ],
            [
                'UserID' => 'U002',
                'Email' => 'an@gmail.com',
                'FullName' => 'Nguyen Van An',
                'PasswordHash' => Hash::make('An@123'),
                'RoleID' => 'R002',
                'Phone' => '0901000002',
                'Status' => true,
                'ProfilePicture' => 'an.jpg',
            ],
            [
                'UserID' => 'U003',
                'Email' => 'binh@gmail.com',
                'FullName' => 'Tran Thi Binh',
                'PasswordHash' => Hash::make('Binh@123'),
                'RoleID' => 'R002',
                'Phone' => '0901000003',
                'Status' => true,
                'ProfilePicture' => 'binh.jpg',
            ],
            [
                'UserID' => 'U004',
                'Email' => 'chi@gmail.com',
                'FullName' => 'Le Minh Chi',
                'PasswordHash' => Hash::make('Chi@123'),
                'RoleID' => 'R002',
                'Phone' => '0901000004',
                'Status' => true,
                'ProfilePicture' => 'chi.jpg',
            ],
            [
                'UserID' => 'U005',
                'Email' => 'dung@gmail.com',
                'FullName' => 'Pham Van Dung',
                'PasswordHash' => Hash::make('Dung@123'),
                'RoleID' => 'R002',
                'Phone' => '0901000005',
                'Status' => true,
                'ProfilePicture' => 'dung.jpg',
            ],
        ]);
    }
}
