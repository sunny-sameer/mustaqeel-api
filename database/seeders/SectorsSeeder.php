<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sectors;
use App\Models\Activities;
use App\Models\SubActivities;
use App\Models\Entities;
use App\Models\Incubator;




class SectorsSeeder extends Seeder
{

    public function run()
    {


        $sectors = [
            [
                "categoryIds" => [1, 2, 3],
                "sectorEn" => "Education",
                "sectorAr" => "التعليم",
            ],
            [
                "categoryIds" => [2],
                "sectorEn" => "Human Health and Social Services Activities",
                "sectorAr" => "الصحة البشرية والخدمات الاجتماعية",
            ],
            [
                "categoryIds" => [2],
                "sectorEn" => "Information & Communication Technology (ICT)",
                "sectorAr" => "تكنولوجيا المعلومات والاتصالات",
            ],
            [
                "categoryIds" => [1, 3],
                "sectorEn" => "Arts & Entairtanment & Sports",
                "sectorAr" => "الفنون و الترفيه و التسلية و الرياضة",
            ],
            [
                "categoryIds" => [1, 3],
                "sectorEn" => "Information & Communication",
                "sectorAr" => "المعلومات و التواصل",
            ],
            [
                "categoryIds" => [1, 3],
                "sectorEn" => "Agriculture, Fisheries, and Water Management",
                "sectorAr" => "الزراعة و صيد السمك و إدارة المياه",
            ],
            [
                "categoryIds" => [1, 3],
                "sectorEn" => "Transportation & Storage",
                "sectorAr" => "خدمات اللوجستية والنقل والتخزين",
            ],
            [
                "categoryIds" => [1, 3],
                "sectorEn" => "Financial services and insurance",
                "sectorAr" => "الخدمات المالية و التأمين",
            ],
            [
                "categoryIds" => [1, 3],
                "sectorEn" => "Health and Social",
                "sectorAr" => "الصحة و الخدمات الاجتماعية",
            ],
            [
                "categoryIds" => [1, 3],
                "sectorEn" => "Tourism",
                "sectorAr" => "السياحة",
            ],
            [
                "categoryIds" => [1, 3],
                "sectorEn" => "Cultural Talents",
                "sectorAr" => "المواهب الثقافية",
            ],
            [
                "categoryIds" => [1, 3],
                "sectorEn" => "Development and Innovation",
                "sectorAr" => "التطوير و الابتكار",
            ],
        ];


        foreach ($sectors as $data) {

            // Create sector if not exists
            $sector = Sectors::firstOrCreate(
                ['name' => $data['sectorEn']],
                ['nameAr' => $data['sectorAr']]
            );

            // Attach to multiple categories at once
            $sector->categories()->syncWithoutDetaching($data['categoryIds']);
        }


        $activities = [
            ['sector_id' => 1, 'name' => 'Academics & Researchers', 'nameAr' => 'الأكاديميين و الباحثين'],
            ['sector_id' => 2, 'name' => 'Doctors', 'nameAr' => 'الأطباء'],
            ['sector_id' => 3, 'name' => 'Inventors', 'nameAr' => 'المخترعين'],
            ['sector_id' => 3, 'name' => 'Specialized Experts', 'nameAr' => 'الخبراء المتخصصون'],
            ['sector_id' => 3, 'name' => 'Digital Industry Leaders', 'nameAr' => 'رواد الصناعة الرقمية'],
        ];


        foreach ($activities as $a) {
            Activities::firstOrCreate(
                ['name' => $a['name']],
                ['sectorId' => $a['sector_id'], 'nameAr' => $a['nameAr']]
            );
        }


        $subActivities = [
            ['activity_id' => 5, 'nameEn' => 'Graduates From a Top Ranked University', 'nameAr' => 'خريجي الجامعات ذات التصنيف العالي'],
            ['activity_id' => 5, 'nameEn' => 'Based on Employment', 'nameAr' => 'على أساس الوظيفة'],
        ];

        foreach ($subActivities as $sa) {
            SubActivities::firstOrCreate(
                ['name' => $sa['nameEn']],
                ['activityId' => $sa['activity_id'], 'nameAr' => $sa['nameAr']]
            );
        }


        // Entities
        $entities = [
            ['activityIds' => [3, 4, 5], 'nameEn' => 'Ministry of Communications and Information Technology', 'nameAr' => 'وزارة الإتصالات وتكنولوجيا المعلومات'],
            ['activityIds' => [1], 'nameEn' => 'Ministry of Education and Higher Education', 'nameAr' => 'وزارة التربية والتعليم والتعليم العالي'],
            ['activityIds' => [2], 'nameEn' => 'Ministry of Public Health', 'nameAr' => 'وزارة الصحة العامة'],
        ];

        foreach ($entities as $e) {
            $entity = Entities::firstOrCreate(
                ['name' => $e['nameEn']],
                ['nameAr' => $e['nameAr']]
            );
            $entity->activities()->syncWithoutDetaching($e['activityIds']);
        }

        // Incubators
        $incubators = [
            ['category_id' => 1, 'nameEn' => 'Qatar Science & Technology Park (QSTP)', 'nameAr' => 'واحة قطر للعلوم والتكنولوجيا'],
            ['category_id' => 1, 'nameEn' => 'Qatar University Business Incubation(QUBI)', 'nameAr' => 'حاضنة أعمال جامعة قطر'],
            ['category_id' => 1, 'nameEn' => 'Qatar Business Incubation Center (QBIC)', 'nameAr' => 'حاضنة قطر للأعمال'],
            ['category_id' => 1, 'nameEn' => 'Digital Incubation Center (DIC)', 'nameAr' => 'حاضنة الأعمال الرقمية'],
        ];

        foreach ($incubators as $i) {
            Incubator::firstOrCreate(
                ['name' => $i['nameEn']],
                ['categoryId' => $i['category_id'], 'nameAr' => $i['nameAr']]
            );
        }
    }
}
