<?php

namespace Database\Seeders;

use App\Models\FormFields;
use App\Models\RequestMetaData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formFields = [
            [
                'formFields'=>[
                    'id'=> 1,
                    'nameEn'=> 'Personal Photo',
                    'nameAr'=> 'صورة شخصية',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'tal',
                        'value' => json_encode(array_filter(['categorySlug'=>'tal'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'ent',
                        'value' => json_encode(array_filter(['categorySlug'=>'ent'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'inv',
                        'value' => json_encode(array_filter(['categorySlug'=>'inv'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'exe',
                        'value' => json_encode(array_filter(['categorySlug'=>'exe'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 2,
                    'nameEn'=> 'Passport Copy',
                    'nameAr'=> 'نسخة من جواز السفر',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=> [
                    [
                        'key' => 'tal',
                        'value' => json_encode(array_filter(['categorySlug'=>'tal'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'ent',
                        'value' => json_encode(array_filter(['categorySlug'=>'ent'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'inv',
                        'value' => json_encode(array_filter(['categorySlug'=>'inv'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'exe',
                        'value' => json_encode(array_filter(['categorySlug'=>'exe'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 3,
                    'nameEn'=> 'Police Clearance/Good Behavior Certificate',
                    'nameAr'=> 'شهادة براءة ذمة من الشرطة / شهادة حسن السيرة والسلوك',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=> [
                    [
                        'key' => 'tal',
                        'value' => json_encode(array_filter(['categorySlug'=>'tal'])),
                        'onshoreOffShore'=> 'offshore',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'ent',
                        'value' => json_encode(array_filter(['categorySlug'=>'ent'])),
                        'onshoreOffShore'=> 'offshore',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'inv',
                        'value' => json_encode(array_filter(['categorySlug'=>'inv'])),
                        'onshoreOffShore'=> 'offshore',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'exe',
                        'value' => json_encode(array_filter(['categorySlug'=>'exe'])),
                        'onshoreOffShore'=> 'offshore',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 4,
                    'nameEn'=> 'Certified Academic Degree',
                    'nameAr'=> 'الشهادة أو  الشهادات التعليمية',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'tal',
                        'value' => json_encode(array_filter(['categorySlug'=>'tal'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'ent',
                        'value' => json_encode(array_filter(['categorySlug'=>'ent'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'inv',
                        'value' => json_encode(array_filter(['categorySlug'=>'inv'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> false,
                    ],
                    [
                        'key' => 'exe',
                        'value' => json_encode(array_filter(['categorySlug'=>'exe'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> false,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 5,
                    'nameEn'=> '3 Months Bank Statement',
                    'nameAr'=> 'كشف حساب بنكي شخصي',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'tal',
                        'value' => json_encode(array_filter(['categorySlug'=>'tal'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'ent',
                        'value' => json_encode(array_filter(['categorySlug'=>'ent'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'inv',
                        'value' => json_encode(array_filter(['categorySlug'=>'inv'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> false,
                    ],
                    [
                        'key' => 'exe',
                        'value' => json_encode(array_filter(['categorySlug'=>'exe'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 6,
                    'nameEn'=> 'CV with Experience letter',
                    'nameAr'=> 'السيرة الداتية مع شهادة الخبرة',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'tal',
                        'value' => json_encode(array_filter(['categorySlug'=>'tal'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'ent',
                        'value' => json_encode(array_filter(['categorySlug'=>'ent'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'inv',
                        'value' => json_encode(array_filter(['categorySlug'=>'inv'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> false,
                    ],
                    [
                        'key' => 'exe',
                        'value' => json_encode(array_filter(['categorySlug'=>'exe'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 7,
                    'nameEn'=> 'Patent Certificate or International Award Certificate (Must be attested)',
                    'nameAr'=> 'شهادة براءة الاختراع أو شهادة جائزة دولية (يجب أن تكون موثقة)',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'tal',
                        'value' => json_encode(array_filter(['categorySlug'=>'tal','sectorSlug' => 'icti','activitySlug' => 'inv'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 8,
                    'nameEn'=> 'Summary of the Invention (1–2 pages)',
                    'nameAr'=> 'نبذة عن الإختراع (من صفحة إلى صفحتين)',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'tal',
                        'value' => json_encode(array_filter(['categorySlug'=>'tal','sectorSlug' => 'icti','activitySlug' => 'inv'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 9,
                    'nameEn'=> 'Recommendation Letter from an ICT Authority',
                    'nameAr'=> 'توصية من جهة مختصة في تكنولوجيا المعلومات والاتصالات',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'tal',
                        'value' => json_encode(array_filter(['categorySlug'=>'tal','sectorSlug' => 'icti','activitySlug' => 'se'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 10,
                    'nameEn'=> 'Proof of University Ranking (screenshot or official document)',
                    'nameAr'=> 'إثبات تصنيف الجامعة (لقطة شاشة أو مستند رسمي)',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'tal',
                        'value' => json_encode(array_filter(['categorySlug'=>'tal','sectorSlug' => 'icti','activitySlug' => 'dil','subActivitySlug' => 'gfatru'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 11,
                    'nameEn'=> 'Board of Directors’ Appointment Decision',
                    'nameAr'=> 'عقد عمل ساري المفعول (يجب أن يوضح الوظيفة والراتب - يجب أن يكون موثقًا)',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'exe',
                        'value' => json_encode(array_filter(['categorySlug'=>'exe'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 12,
                    'nameEn'=> 'Valid Company CR Copy',
                    'nameAr'=> 'نسخة من السجل التجاري للشركة ساري',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'exe',
                        'value' => json_encode(array_filter(['categorySlug'=>'exe'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 13,
                    'nameEn'=> 'Recently issued Employment Letter',
                    'nameAr'=> 'شهادة عمل',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'exe',
                        'value' => json_encode(array_filter(['categorySlug'=>'exe'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 14,
                    'nameEn'=> 'Health Insurance',
                    'nameAr'=> 'التأمين الصحي',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'exe',
                        'value' => json_encode(array_filter(['categorySlug'=>'exe'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> false,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 15,
                    'nameEn'=> 'Valid Employment Contract (Must show role and salary– Must be attested)',
                    'nameAr'=> 'عقد عمل ساري المفعول (يجب أن يوضح الوظيفة والراتب - يجب أن يكون موثقًا)',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'tal',
                        'value' => json_encode(array_filter(['categorySlug'=>'tal','sectorSlug' => 'icti','activitySlug' => 'dil','subActivitySlug' => 'boe'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'exe',
                        'value' => json_encode(array_filter(['categorySlug'=>'exe'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 16,
                    'nameEn'=> 'Job Contract / Financial Capacity Statement',
                    'nameAr'=> 'عقد العمل / شهادة القدرة المالية',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'tal',
                        'value' => json_encode(array_filter(['categorySlug'=>'tal'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ]
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 17,
                    'nameEn'=> 'Valid Commercial Registration (CR)',
                    'nameAr'=> 'سجل تجاري ساري المفعول',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'inv',
                        'value' => json_encode(array_filter(['categorySlug'=>'inv'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 18,
                    'nameEn'=> 'Tax report for the last three years',
                    'nameAr'=> 'تقرير ضريبي لآخر ثلاث سنوات',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'inv',
                        'value' => json_encode(array_filter(['categorySlug'=>'inv'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 19,
                    'nameEn'=> 'Establishment Card/QID of the Sponsor',
                    'nameAr'=> 'بطاقة قيد المنشأة / البطاقة الشخصية للكفيل',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'tal',
                        'value' => json_encode(array_filter(['categorySlug'=>'tal'])),
                        'onshoreOffShore'=> 'onshore',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'ent',
                        'value' => json_encode(array_filter(['categorySlug'=>'ent'])),
                        'onshoreOffShore'=> 'onshore',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'inv',
                        'value' => json_encode(array_filter(['categorySlug'=>'inv'])),
                        'onshoreOffShore'=> 'onshore',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'exe',
                        'value' => json_encode(array_filter(['categorySlug'=>'exe'])),
                        'onshoreOffShore'=> 'onshore',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 20,
                    'nameEn'=> 'QID Copy (For Qatar residents)',
                    'nameAr'=> 'نسخة من البطاقة القطرية (للمقيمين)',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'tal',
                        'value' => json_encode(array_filter(['categorySlug'=>'tal'])),
                        'onshoreOffShore'=> 'onshore',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'ent',
                        'value' => json_encode(array_filter(['categorySlug'=>'ent'])),
                        'onshoreOffShore'=> 'onshore',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'inv',
                        'value' => json_encode(array_filter(['categorySlug'=>'inv'])),
                        'onshoreOffShore'=> 'onshore',
                        'isRequired'=> true,
                    ],
                    [
                        'key' => 'exe',
                        'value' => json_encode(array_filter(['categorySlug'=>'exe'])),
                        'onshoreOffShore'=> 'onshore',
                        'isRequired'=> true,
                    ],
                ]
            ],
            [
                'formFields'=>[
                    'id'=> 21,
                    'nameEn'=> 'Asset-Based Audit Report',
                    'nameAr'=> 'تقرير التدقيق القائم على الأصول',
                    'type'=> 'file',
                    'meta'=> json_encode(array_filter(["extensions"=>["xlsx","xlsb","xls","xltx","xlsm","csv"]])),
                    'status'=> 1
                ],
                'metas'=>[
                    [
                        'key' => 'inv',
                        'value' => json_encode(array_filter(['categorySlug'=>'inv','subCategorySlug'=>'ab'])),
                        'onshoreOffShore'=> 'both',
                        'isRequired'=> true,
                    ],
                ]
            ],
        ];

        foreach ($formFields as $key => $value) {
            // $ff = FormFields::create($value['formFields']);
            // $ff->formMetas()->createMany($value['metas']);
            $ff = FormFields::withTrashed()->updateOrCreate(['id' => $value['formFields']['id']],$value['formFields']);
            if ($ff->trashed()) {
                $ff->restore();
            }
            foreach ($value['metas'] as $meta) {
                $m = $ff->formMetas()->withTrashed()->updateOrCreate(['key' => $meta['key']],$meta);

                if ($m->trashed()) {
                    $m->restore();
                }
            }
        }
    }
}
