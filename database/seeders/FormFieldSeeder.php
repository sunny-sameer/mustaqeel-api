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
                    'nameEn'=> 'Personal Photo',
                    'nameAr'=> 'صورة شخصية',
                    'type'=> 'file',
                    'onshoreOffShore'=> 'both',
                    'isRequired'=> true,
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png"]])),
                    'status'=> 1
                ],
                'metas'=>['tal','ent','inv']
            ],
            [
                'formFields'=>[
                    'nameEn'=> 'Passport Copy',
                    'nameAr'=> 'نسخة من جواز السفر',
                    'type'=> 'file',
                    'onshoreOffShore'=> 'both',
                    'isRequired'=> true,
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>['tal','ent','inv']
            ],
            [
                'formFields'=>[
                    'nameEn'=> 'Police Clearance/Good Behavior Certificate',
                    'nameAr'=> 'شهادة براءة ذمة من الشرطة / شهادة حسن السيرة والسلوك',
                    'type'=> 'file',
                    'onshoreOffShore'=> 'offshore',
                    'isRequired'=> true,
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>['tal','ent','inv']
            ],
            [
                'formFields'=>[
                    'nameEn'=> 'Certified Academic Degree',
                    'nameAr'=> 'الشهادة أو  الشهادات التعليمية',
                    'type'=> 'file',
                    'onshoreOffShore'=> 'both',
                    'isRequired'=> true,
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>['tal','ent']
            ],
            [
                'formFields'=>[
                    'nameEn'=> 'Certified Academic Degree',
                    'nameAr'=> 'الشهادة أو  الشهادات التعليمية',
                    'type'=> 'file',
                    'onshoreOffShore'=> 'both',
                    'isRequired'=> false,
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>['inv']
            ],
            [
                'formFields'=>[
                    'nameEn'=> '3 Months Bank Statement',
                    'nameAr'=> 'كشف حساب بنكي شخصي',
                    'type'=> 'file',
                    'onshoreOffShore'=> 'both',
                    'isRequired'=> true,
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>['tal','ent']
            ],
            [
                'formFields'=>[
                    'nameEn'=> '3 Months Bank Statement',
                    'nameAr'=> 'كشف حساب بنكي شخصي',
                    'type'=> 'file',
                    'onshoreOffShore'=> 'both',
                    'isRequired'=> false,
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>['inv']
            ],
            [
                'formFields'=>[
                    'nameEn'=> 'CV with Experience letter',
                    'nameAr'=> 'السيرة الداتية مع شهادة الخبرة',
                    'type'=> 'file',
                    'onshoreOffShore'=> 'both',
                    'isRequired'=> true,
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>['tal','ent']
            ],
            [
                'formFields'=>[
                    'nameEn'=> 'CV with Experience letter',
                    'nameAr'=> 'السيرة الداتية مع شهادة الخبرة',
                    'type'=> 'file',
                    'onshoreOffShore'=> 'both',
                    'isRequired'=> false,
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>['inv']
            ],
            [
                'formFields'=>[
                    'nameEn'=> 'Job Contract / Financial Capacity Statement',
                    'nameAr'=> 'عقد العمل / شهادة القدرة المالية',
                    'type'=> 'file',
                    'onshoreOffShore'=> 'both',
                    'isRequired'=> true,
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>['tal']
            ],
            [
                'formFields'=>[
                    'nameEn'=> 'Valid Commercial Registration (CR)',
                    'nameAr'=> 'سجل تجاري ساري المفعول',
                    'type'=> 'file',
                    'onshoreOffShore'=> 'both',
                    'isRequired'=> true,
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>['inv']
            ],
            [
                'formFields'=>[
                    'nameEn'=> 'Tax report for the last three years',
                    'nameAr'=> 'تقرير ضريبي لآخر ثلاث سنوات',
                    'type'=> 'file',
                    'onshoreOffShore'=> 'both',
                    'isRequired'=> true,
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>['inv']
            ],
            [
                'formFields'=>[
                    'nameEn'=> 'Establishment Card/QID of the Sponsor',
                    'nameAr'=> 'بطاقة قيد المنشأة / البطاقة الشخصية للكفيل',
                    'type'=> 'file',
                    'onshoreOffShore'=> 'onshore',
                    'isRequired'=> true,
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>['tal','ent','inv']
            ],
            [
                'formFields'=>[
                    'nameEn'=> 'QID Copy (For Qatar residents)',
                    'nameAr'=> 'نسخة من البطاقة القطرية (للمقيمين)',
                    'type'=> 'file',
                    'onshoreOffShore'=> 'onshore',
                    'isRequired'=> true,
                    'meta'=> json_encode(array_filter(["extensions"=>["jpg","jpeg","png","pdf","doc","docx"]])),
                    'status'=> 1
                ],
                'metas'=>['tal','ent','inv']
            ],
        ];

        foreach ($formFields as $key => $value) {
            foreach ($value['metas'] as $key2 => $value2) {
                $ff = FormFields::create($value['formFields']);
                RequestMetaData::create([
                    'modelId' => $ff->id,
                    'catSlug' => $value2,
                    'modelType' => FormFields::class
                ]);
            }
        }
    }
}
