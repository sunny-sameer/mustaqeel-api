<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categories;
use App\Models\SubCategories;



class CategoriesSeeder extends Seeder
{

    public function run()
    {


        $categories = [
            [
                'id'=>1,
                'en' => 'Entrepreneur',
                'ar' => 'رواد الأعمال',
                'subcats' => ''
            ],
            [
                'id'=>2,
                'en' => 'Talent',
                'ar' => 'المواهب',
                'subcats' => ''
            ],
            [
                'id'=>3,
                'en' => 'Investor',
                'ar' => 'مستثمر',
                'subcats' => [[
                    'en' => 'assets based',
                    'ar' => 'القائمة على الأصول'
                ], [
                    'en' => 'tax based',
                    'ar' => 'على أساس الضرائب'
                ]]
                ],
            [
                'id'=>4,
                'en' => 'Executive',
                'ar' => 'المدراء التنفيذيين',
                'subcats' => ''
            ],
        ];



        foreach ($categories as $category) {
            $catId = $category['id'];
            $catNameEn = trim($category['en']);
            $catNameAr = trim($category['ar']);

            $status =  1;

            $categoryVal = ['id' => $catId, 'name' => $catNameEn, 'nameAr' => $catNameAr, 'status' => $status];
            $categoryRec = Categories::create($categoryVal);

            if ($category['subcats']) {
                $sub_categories = $category['subcats'];


                foreach ($sub_categories as $sub_category) {
                    $subCatNameEn = trim($sub_category['en']);
                    $subCatNameAr = trim($sub_category['ar']);

                    $subCategoryVal = ['categoryId' => $categoryRec->id, 'name' => $subCatNameEn, 'nameAr' => $subCatNameAr, 'status' => $status];

                    SubCategories::create($subCategoryVal);
                }
            }
        }
    }
}
