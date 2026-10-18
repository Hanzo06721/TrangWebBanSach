<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Dữ liệu mẫu đang để PasswordHash = 'hash_admin', 'hash_an'... (không phải bcrypt) nên không đăng nhập được.
 * Seeder này đặt mật khẩu của TẤT CẢ tài khoản mẫu thành: 123456
 *
 * Chạy:  php artisan db:seed --class=ResetSamplePasswordSeeder
 */
class ResetSamplePasswordSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Users')->update(['PasswordHash' => Hash::make('123456')]);

        $this->command?->info('Đã đặt mật khẩu 123456 cho toàn bộ tài khoản mẫu.');
    }
}
