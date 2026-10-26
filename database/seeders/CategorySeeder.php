<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories=[
            [
                'CategoryID' => 'C001',
                'CategoryName' => 'Lập trình',
                'Description' => 'Sách về lập trình và phát triển phần mềm',
                'Status' => true,
            ],
            [
                'CategoryID' => 'C002',
                'CategoryName' => 'Văn học',
                'Description' => 'Sách văn học Việt Nam và nước ngoài',
                'Status' => true,
            ],
            [
                'CategoryID' => 'C003',
                'CategoryName' => 'Kinh tế',
                'Description' => 'Sách về kinh tế và kinh doanh',
                'Status' => true,
            ],
            [
                'CategoryID' => 'C004',
                'CategoryName' => 'Ngoại ngữ',
                'Description' => 'Sách học ngoại ngữ Tiếng Anh, Trung, Nhật, Hàn,...',
                'Status' => true,
            ],
            [
                'CategoryID' => 'C005',
                'CategoryName' => 'Kỹ năng sống',
                'Description' => 'Sách phát triển bản thân và kỹ năng sống',
                'Status' => true,
            ],
        ];

        foreach( $categories as $category ) {
            DB::table('categories')->updateOrInsert(
                ['CategoryID'=>$category['CategoryID']],
                [
                    'CategoryName'=>($category['CategoryName']) ? $category['CategoryName'] :'',
                    'Description'=>$category['Description'],
                    'Status'=>$category['Status']?1:0,
                ]
            );

        }
    }
}
