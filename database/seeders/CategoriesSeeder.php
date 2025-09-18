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
                'en' => 'Entrepreneur',
                'ar' => 'رواد الأعمال',
                'subcats' => ''
            ],
            [
                'en' => 'Talent',
                'ar' => 'المواهب',
                'subcats' => ''
            ],
            [
                'en' => 'Investor',
                'ar' => 'مستثمر',
                'subcats' => [[
                    'en' => 'assets based',
                    'ar' => 'القائمة على الأصول'
                ], [
                    'en' => 'tax based',
                    'ar' => 'على أساس الضرائب'
                ]]
            ]
        ];



        foreach ($categories as $category) {
            $catNameEn = trim($category['en']);
            $catNameAr = trim($category['ar']);

            $status =  1;

            $categoryVal = ['name' => $catNameEn, 'nameAr' => $catNameAr, 'status' => $status];
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
