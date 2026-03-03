<?php
// database/seeders/FormFieldSeeder.php

namespace Database\Seeders;

use App\Models\FormFieldMeta;
use App\Models\FormFields;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FormFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formFields = [
            // ==================== SECTION 1: CATEGORY & BUSINESS STRUCTURE ====================
            
            // Group: Identification Data
            [
                'formFields' => [
                    'nameEn' => 'Category',
                    'nameAr' => 'الفئة',
                    'type' => 'select',
                    'section' => 'personal-info',
                    'group' => 'identification-data',
                    'field_order' => 1,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'options' => [
                            ['labelEn' => 'Talent', 'labelAr' => 'موهبة', 'value' => 'tal'],
                            ['labelEn' => 'Entrepreneur', 'labelAr' => 'رائد أعمال', 'value' => 'ent'],
                            ['labelEn' => 'Investor', 'labelAr' => 'مستثمر', 'value' => 'inv'],
                            ['labelEn' => 'Executive', 'labelAr' => 'تنفيذي', 'value' => 'exe']
                        ],
                        'placeholderEn' => 'Select Category',
                        'placeholderAr' => 'اختر الفئة',
                        'validations' => ['required' => true]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // No metas = visible to all categories
            ],
            
            // Sub Category
            [
                'formFields' => [
                    'nameEn' => 'Sub Category',
                    'nameAr' => 'الفئة الفرعية',
                    'type' => 'select',
                    'section' => 'personal-info',
                    'group' => 'identification-data',
                    'field_order' => 2,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Select Sub Category',
                        'placeholderAr' => 'اختر الفئة الفرعية',
                        'validations' => ['required' => false],
                        'depends_on' => 'category'
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'category', 'operator' => 'notEmpty', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [] // Visible to all categories (but depends on category having sub-categories)
            ],
            
            // Sector
            [
                'formFields' => [
                    'nameEn' => 'Sector',
                    'nameAr' => 'القطاع',
                    'type' => 'select',
                    'section' => 'personal-info',
                    'group' => 'identification-data',
                    'field_order' => 3,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Select Sector',
                        'placeholderAr' => 'اختر القطاع',
                        'validations' => ['required' => false],
                        'depends_on' => 'category'
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'category', 'operator' => 'in', 'value' => ['tal', 'ent', 'exe'], 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'tal', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => false],
                    ['key' => 'ent', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => false],
                    ['key' => 'exe', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => false]
                ]
            ],
            
            // Activity
            [
                'formFields' => [
                    'nameEn' => 'Activity',
                    'nameAr' => 'النشاط',
                    'type' => 'select',
                    'section' => 'personal-info',
                    'group' => 'identification-data',
                    'field_order' => 4,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Select Activity',
                        'placeholderAr' => 'اختر النشاط',
                        'validations' => ['required' => false],
                        'depends_on' => 'sector'
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'category', 'operator' => 'in', 'value' => ['tal', 'ent', 'exe'], 'action' => 'show'],
                        ['field' => 'sector', 'operator' => 'notEmpty', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'tal', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => false],
                    ['key' => 'ent', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => false],
                    ['key' => 'exe', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => false]
                ]
            ],
            
            // Sub Activity
            [
                'formFields' => [
                    'nameEn' => 'Sub Activity',
                    'nameAr' => 'النشاط الفرعي',
                    'type' => 'select',
                    'section' => 'personal-info',
                    'group' => 'identification-data',
                    'field_order' => 5,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Select Sub Activity',
                        'placeholderAr' => 'اختر النشاط الفرعي',
                        'validations' => ['required' => false],
                        'depends_on' => 'activity'
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'category', 'operator' => 'in', 'value' => ['tal', 'ent', 'exe'], 'action' => 'show'],
                        ['field' => 'activity', 'operator' => 'notEmpty', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'tal', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => false],
                    ['key' => 'ent', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => false],
                    ['key' => 'exe', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => false]
                ]
            ],
            
            // Entity - Show only for Talent
            [
                'formFields' => [
                    'nameEn' => 'Entity',
                    'nameAr' => 'الكيان',
                    'type' => 'select',
                    'section' => 'personal-info',
                    'group' => 'identification-data',
                    'field_order' => 6,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Select Entity',
                        'placeholderAr' => 'اختر الكيان',
                        'validations' => ['required' => false],
                        'depends_on' => 'activity'
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'category', 'operator' => 'equals', 'value' => 'tal', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'tal', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => false]
                ]
            ],
            
            // Incubator - Show only for Entrepreneur
            [
                'formFields' => [
                    'nameEn' => 'Incubator',
                    'nameAr' => 'الحاضنة',
                    'type' => 'select',
                    'section' => 'personal-info',
                    'group' => 'identification-data',
                    'field_order' => 7,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Select Incubator',
                        'placeholderAr' => 'اختر الحاضنة',
                        'validations' => ['required' => false],
                        'depends_on' => 'category'
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'category', 'operator' => 'equals', 'value' => 'ent', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'ent', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => false]
                ]
            ],

            // ==================== SECTION 2: PERSONAL INFORMATION (All Categories) ====================
            
            // Group: Applicant Information
            [
                'formFields' => [
                    'nameEn' => 'Full Name (English)',
                    'nameAr' => 'الاسم الكامل (إنجليزي)',
                    'type' => 'text',
                    'section' => 'personal-info',
                    'group' => 'applicant-info',
                    'field_order' => 1,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Enter your full name',
                        'placeholderAr' => 'أدخل اسمك الكامل',
                        'tooltipEn' => '• English Name is required • Only alphabetic characters are allowed',
                        'tooltipAr' => '• الاسم بالإنجليزية مطلوب • يُسمح فقط بالأحرف الأبجدية',
                        'validations' => [
                            'required' => true,
                            'pattern' => '^[a-zA-Z\\s]+$',
                            'minLength' => 2,
                            'maxLength' => 100
                        ]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Full Name (Arabic)',
                    'nameAr' => 'الاسم الكامل (عربي)',
                    'type' => 'text',
                    'section' => 'personal-info',
                    'group' => 'applicant-info',
                    'field_order' => 2,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Enter your name in Arabic',
                        'placeholderAr' => 'أدخل اسمك بالعربية',
                        'tooltipEn' => '• Only Arabic characters are allowed',
                        'tooltipAr' => '• يُسمح فقط بالأحرف العربية',
                        'validations' => [
                            'required' => true,
                            'pattern' => '^[\\p{Arabic}\\s]+$',
                            'minLength' => 2,
                            'maxLength' => 100
                        ]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Gender',
                    'nameAr' => 'الجنس',
                    'type' => 'select',
                    'section' => 'personal-info',
                    'group' => 'applicant-info',
                    'field_order' => 3,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'options' => [
                            ['labelEn' => 'Male', 'labelAr' => 'ذكر', 'value' => 'male'],
                            ['labelEn' => 'Female', 'labelAr' => 'أنثى', 'value' => 'female']
                        ],
                        'placeholderEn' => 'Select Gender',
                        'placeholderAr' => 'اختر الجنس',
                        'validations' => ['required' => true]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Date of Birth',
                    'nameAr' => 'تاريخ الميلاد',
                    'type' => 'date',
                    'section' => 'personal-info',
                    'group' => 'applicant-info',
                    'field_order' => 4,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Select date of birth',
                        'placeholderAr' => 'اختر تاريخ الميلاد',
                        'validations' => ['required' => true]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Religion',
                    'nameAr' => 'الديانة',
                    'type' => 'select',
                    'section' => 'personal-info',
                    'group' => 'applicant-info',
                    'field_order' => 5,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'options' => [
                            ['labelEn' => 'Muslim', 'labelAr' => 'مسلم', 'value' => 'muslim'],
                            ['labelEn' => 'Christian', 'labelAr' => 'مسيحي', 'value' => 'christian'],
                            ['labelEn' => 'Jewish', 'labelAr' => 'يهودي', 'value' => 'jewish'],
                            ['labelEn' => 'Hindu', 'labelAr' => 'هندوسي', 'value' => 'hindu'],
                            ['labelEn' => 'Buddhist', 'labelAr' => 'بوذي', 'value' => 'buddhist'],
                            ['labelEn' => 'Other', 'labelAr' => 'أخرى', 'value' => 'other']
                        ],
                        'placeholderEn' => 'Select Religion',
                        'placeholderAr' => 'اختر الديانة',
                        'validations' => ['required' => true]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Marital Status',
                    'nameAr' => 'الحالة الاجتماعية',
                    'type' => 'select',
                    'section' => 'personal-info',
                    'group' => 'applicant-info',
                    'field_order' => 6,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'options' => [
                            ['labelEn' => 'Single', 'labelAr' => 'أعزب', 'value' => 'single'],
                            ['labelEn' => 'Married', 'labelAr' => 'متزوج', 'value' => 'married'],
                            ['labelEn' => 'Divorced', 'labelAr' => 'مطلق', 'value' => 'divorced'],
                            ['labelEn' => 'Widowed', 'labelAr' => 'أرمل', 'value' => 'widowed']
                        ],
                        'placeholderEn' => 'Select Marital Status',
                        'placeholderAr' => 'اختر الحالة الاجتماعية',
                        'validations' => ['required' => true]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Place of Birth',
                    'nameAr' => 'مكان الميلاد',
                    'type' => 'select',
                    'section' => 'personal-info',
                    'group' => 'applicant-info',
                    'field_order' => 7,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Select country of birth',
                        'placeholderAr' => 'اختر بلد الميلاد',
                        'validations' => ['required' => true],
                        'options_from' => 'nationalities'
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Current Country of Residence',
                    'nameAr' => 'بلد الإقامة الحالي',
                    'type' => 'select',
                    'section' => 'personal-info',
                    'group' => 'applicant-info',
                    'field_order' => 8,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Select country of residence',
                        'placeholderAr' => 'اختر بلد الإقامة',
                        'validations' => ['required' => true],
                        'options_from' => 'nationalities'
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Nationality',
                    'nameAr' => 'الجنسية',
                    'type' => 'select',
                    'section' => 'personal-info',
                    'group' => 'applicant-info',
                    'field_order' => 9,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Select nationality',
                        'placeholderAr' => 'اختر الجنسية',
                        'validations' => ['required' => true],
                        'options_from' => 'nationalities'
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Short Biography',
                    'nameAr' => 'السيرة الذاتية المختصرة',
                    'type' => 'textarea',
                    'section' => 'personal-info',
                    'group' => 'applicant-info',
                    'field_order' => 10,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Write a short biography',
                        'placeholderAr' => 'اكتب سيرة ذاتية مختصرة',
                        'tooltipEn' => '• Short Bio is required • Any special Character are not allowed',
                        'tooltipAr' => '• السيرة الذاتية المختصرة مطلوبة • لا يُسمح بأي أحرف خاصة',
                        'validations' => [
                            'required' => true,
                            'minLength' => 10,
                            'maxLength' => 500
                        ],
                        'rows' => 5
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],

            // Group: Contact Information
            [
                'formFields' => [
                    'nameEn' => 'Email Address',
                    'nameAr' => 'البريد الإلكتروني',
                    'type' => 'email',
                    'section' => 'personal-info',
                    'group' => 'contact-info',
                    'field_order' => 1,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Enter your email',
                        'placeholderAr' => 'أدخل بريدك الإلكتروني',
                        'tooltipEn' => '• Email is required • only email pattern are allowed',
                        'tooltipAr' => '• البريد الإلكتروني مطلوب • يُسمح فقط بنمط البريد الإلكتروني',
                        'validations' => [
                            'required' => true,
                            'email' => true,
                            'maxLength' => 255
                        ]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Mobile Number',
                    'nameAr' => 'رقم الجوال',
                    'type' => 'text',
                    'section' => 'personal-info',
                    'group' => 'contact-info',
                    'field_order' => 2,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Enter mobile number',
                        'placeholderAr' => 'أدخل رقم الجوال',
                        'tooltipEn' => '• Mobile Number is required • only numeric characters are allowed',
                        'tooltipAr' => '• رقم الجوال مطلوب • يُسمح فقط بالأرقام',
                        'validations' => [
                            'required' => true,
                            'pattern' => '^[0-9+\-\s]+$',
                            'minLength' => 8,
                            'maxLength' => 15
                        ]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Phone Number',
                    'nameAr' => 'رقم الهاتف',
                    'type' => 'text',
                    'section' => 'personal-info',
                    'group' => 'contact-info',
                    'field_order' => 3,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Enter phone number',
                        'placeholderAr' => 'أدخل رقم الهاتف',
                        'tooltipEn' => '• only numeric characters are allowed',
                        'tooltipAr' => '• يُسمح فقط بالأرقام',
                        'validations' => [
                            'required' => false,
                            'pattern' => '^[0-9+\-\s]+$',
                            'maxLength' => 15
                        ]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Permanent Address in the country of origin',
                    'nameAr' => 'العنوان الدائم في بلد المنشأ',
                    'type' => 'textarea',
                    'section' => 'personal-info',
                    'group' => 'contact-info',
                    'field_order' => 4,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Enter your permanent address',
                        'placeholderAr' => 'أدخل عنوانك الدائم',
                        'tooltipEn' => '• Permanent Address is required • Only those Special Characters are allowed ( -, ( , ) ., +, $,@, )',
                        'tooltipAr' => '• العنوان الدائم مطلوب • يُسمح فقط بهذه الأحرف الخاصة ( -, ( , ) ., +, $,@, )',
                        'validations' => [
                            'required' => true,
                            'maxLength' => 500
                        ],
                        'rows' => 3
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'PO Box',
                    'nameAr' => 'صندوق بريد',
                    'type' => 'text',
                    'section' => 'personal-info',
                    'group' => 'contact-info',
                    'field_order' => 5,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Enter PO Box',
                        'placeholderAr' => 'أدخل صندوق البريد',
                        'tooltipEn' => '• Alphanumeric characters are allowed',
                        'tooltipAr' => '• يُسمح بالأحرف الأبجدية الرقمية',
                        'validations' => [
                            'required' => false,
                            'pattern' => '^[a-zA-Z0-9\-\s]+$',
                            'maxLength' => 50
                        ]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],

            // Group: Passport Details
            [
                'formFields' => [
                    'nameEn' => 'Type of Passport',
                    'nameAr' => 'نوع جواز السفر',
                    'type' => 'select',
                    'section' => 'personal-info',
                    'group' => 'passport-details',
                    'field_order' => 1,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'options' => [
                            ['labelEn' => 'Ordinary', 'labelAr' => 'عادي', 'value' => 'ordinary'],
                            ['labelEn' => 'Diplomatic', 'labelAr' => 'دبلوماسي', 'value' => 'diplomatic'],
                            ['labelEn' => 'Service', 'labelAr' => 'خدمة', 'value' => 'service'],
                            ['labelEn' => 'Official', 'labelAr' => 'رسمي', 'value' => 'official']
                        ],
                        'placeholderEn' => 'Select passport type',
                        'placeholderAr' => 'اختر نوع جواز السفر',
                        'validations' => ['required' => true]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Passport Number',
                    'nameAr' => 'رقم جواز السفر',
                    'type' => 'text',
                    'section' => 'personal-info',
                    'group' => 'passport-details',
                    'field_order' => 2,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Enter passport number',
                        'placeholderAr' => 'أدخل رقم جواز السفر',
                        'tooltipEn' => '• Passport Number is required • Only alphanumeric characters are allowed',
                        'tooltipAr' => '• رقم جواز السفر مطلوب • يُسمح فقط بالأحرف الأبجدية الرقمية',
                        'validations' => [
                            'required' => true,
                            'pattern' => '^[a-zA-Z0-9]+$',
                            'minLength' => 5,
                            'maxLength' => 20
                        ]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Passport Issue Date',
                    'nameAr' => 'تاريخ إصدار جواز السفر',
                    'type' => 'date',
                    'section' => 'personal-info',
                    'group' => 'passport-details',
                    'field_order' => 3,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Select issue date',
                        'placeholderAr' => 'اختر تاريخ الإصدار',
                        'validations' => ['required' => true]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Passport Expiry Date',
                    'nameAr' => 'تاريخ انتهاء جواز السفر',
                    'type' => 'date',
                    'section' => 'personal-info',
                    'group' => 'passport-details',
                    'field_order' => 4,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Select expiry date',
                        'placeholderAr' => 'اختر تاريخ الانتهاء',
                        'validations' => [
                            'required' => true,
                            'after' => 'passport_issue_date'
                        ]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Passport Issuing Country',
                    'nameAr' => 'بلد إصدار جواز السفر',
                    'type' => 'select',
                    'section' => 'personal-info',
                    'group' => 'passport-details',
                    'field_order' => 5,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Select issuing country',
                        'placeholderAr' => 'اختر بلد الإصدار',
                        'validations' => ['required' => true],
                        'options_from' => 'nationalities'
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Passport Issue By',
                    'nameAr' => 'جهة الإصدار',
                    'type' => 'text',
                    'section' => 'personal-info',
                    'group' => 'passport-details',
                    'field_order' => 6,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Enter issuing authority',
                        'placeholderAr' => 'أدخل جهة الإصدار',
                        'tooltipEn' => '• Passport issue by is required • only alphabetic characters are allowed',
                        'tooltipAr' => '• جهة الإصدار مطلوبة • يُسمح فقط بالأحرف الأبجدية',
                        'validations' => [
                            'required' => true,
                            'pattern' => '^[a-zA-Z\s]+$',
                            'maxLength' => 100
                        ]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            [
                'formFields' => [
                    'nameEn' => 'Passport Place Of Issue',
                    'nameAr' => 'مكان إصدار جواز السفر',
                    'type' => 'text',
                    'section' => 'personal-info',
                    'group' => 'passport-details',
                    'field_order' => 7,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Enter place of issue',
                        'placeholderAr' => 'أدخل مكان الإصدار',
                        'tooltipEn' => '• Passport place of issue is required • only alphabetic characters are required',
                        'tooltipAr' => '• مكان الإصدار مطلوب • يُسمح فقط بالأحرف الأبجدية',
                        'validations' => [
                            'required' => true,
                            'pattern' => '^[a-zA-Z\s]+$',
                            'maxLength' => 100
                        ]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],

            // Group: Previous Jobs (Repeatable)
            [
                'formFields' => [
                    'nameEn' => 'Previous Jobs',
                    'nameAr' => 'الوظائف السابقة',
                    'type' => 'group',
                    'section' => 'employment-education',
                    'group' => 'previous-jobs',
                    'field_order' => 10,
                    'grid_columns' => 12,
                    'repeatable' => true,
                    'repeatable_label' => 'Add Previous Job',
                    'repeatable_max' => 5,
                    'meta' => json_encode([
                        'fields' => [
                            [
                                'nameEn' => 'Entity',
                                'nameAr' => 'الجهة',
                                'type' => 'text',
                                'grid_columns' => 4,
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Job Title',
                                'nameAr' => 'المسمى الوظيفي',
                                'type' => 'text',
                                'grid_columns' => 4,
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Job Duration',
                                'nameAr' => 'المدة',
                                'type' => 'select',
                                'grid_columns' => 4,
                                'options' => [
                                    ['labelEn' => 'Less than 1 year', 'labelAr' => 'أقل من سنة', 'value' => '<1'],
                                    ['labelEn' => '1-3 years', 'labelAr' => '1-3 سنوات', 'value' => '1-3'],
                                    ['labelEn' => '3-5 years', 'labelAr' => '3-5 سنوات', 'value' => '3-5'],
                                    ['labelEn' => '5-10 years', 'labelAr' => '5-10 سنوات', 'value' => '5-10'],
                                    ['labelEn' => 'More than 10 years', 'labelAr' => 'أكثر من 10 سنوات', 'value' => '>10']
                                ],
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Job Country',
                                'nameAr' => 'بلد العمل',
                                'type' => 'select',
                                'grid_columns' => 4,
                                'options_from' => 'nationalities',
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Current / Previous',
                                'nameAr' => 'حالي / سابق',
                                'type' => 'select',
                                'grid_columns' => 4,
                                'options' => [
                                    ['labelEn' => 'Current', 'labelAr' => 'حالي', 'value' => 'current'],
                                    ['labelEn' => 'Previous', 'labelAr' => 'سابق', 'value' => 'previous']
                                ],
                                'validations' => ['required' => true]
                            ]
                        ]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],

            // Group: Education (Repeatable)
            [
                'formFields' => [
                    'nameEn' => 'Education',
                    'nameAr' => 'التعليم',
                    'type' => 'group',
                    'section' => 'employment-education',
                    'group' => 'education',
                    'field_order' => 20,
                    'grid_columns' => 12,
                    'repeatable' => true,
                    'repeatable_label' => 'Add Education',
                    'repeatable_max' => 10,
                    'meta' => json_encode([
                        'fields' => [
                            [
                                'nameEn' => 'Qualification - Certificate',
                                'nameAr' => 'المؤهل - الشهادة',
                                'type' => 'select',
                                'grid_columns' => 4,
                                'options' => [
                                    ['labelEn' => 'High School', 'labelAr' => 'ثانوية عامة', 'value' => 'high_school'],
                                    ['labelEn' => 'Diploma', 'labelAr' => 'دبلوم', 'value' => 'diploma'],
                                    ['labelEn' => 'Bachelor\'s Degree', 'labelAr' => 'بكالوريوس', 'value' => 'bachelor'],
                                    ['labelEn' => 'Master\'s Degree', 'labelAr' => 'ماجستير', 'value' => 'master'],
                                    ['labelEn' => 'PhD', 'labelAr' => 'دكتوراه', 'value' => 'phd'],
                                    ['labelEn' => 'Post-doctoral', 'labelAr' => 'ما بعد الدكتوراه', 'value' => 'postdoc']
                                ],
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'University/College',
                                'nameAr' => 'الجامعة/الكلية',
                                'type' => 'text',
                                'grid_columns' => 4,
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Country',
                                'nameAr' => 'البلد',
                                'type' => 'select',
                                'grid_columns' => 4,
                                'options_from' => 'nationalities',
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Specialization',
                                'nameAr' => 'التخصص',
                                'type' => 'text',
                                'grid_columns' => 4,
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Period',
                                'nameAr' => 'المدة',
                                'type' => 'select',
                                'grid_columns' => 4,
                                'options' => [
                                    ['labelEn' => '1 year', 'labelAr' => 'سنة', 'value' => '1'],
                                    ['labelEn' => '2 years', 'labelAr' => 'سنتان', 'value' => '2'],
                                    ['labelEn' => '3 years', 'labelAr' => '3 سنوات', 'value' => '3'],
                                    ['labelEn' => '4 years', 'labelAr' => '4 سنوات', 'value' => '4'],
                                    ['labelEn' => '5 years', 'labelAr' => '5 سنوات', 'value' => '5'],
                                    ['labelEn' => '6+ years', 'labelAr' => '6+ سنوات', 'value' => '6+']
                                ],
                                'validations' => ['required' => true]
                            ]
                        ]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],

            // ==================== SECTION 3: RESIDENCY INFORMATION ====================
            
            // Group: Residency Details
            [
                'formFields' => [
                    'nameEn' => 'Are you Qatar Resident?',
                    'nameAr' => 'هل أنت مقيم في قطر؟',
                    'type' => 'checkbox',
                    'section' => 'residency-travel',
                    'group' => 'residency-details',
                    'field_order' => 1,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'options' => [
                            ['labelEn' => 'Yes, I am a Qatar resident', 'labelAr' => 'نعم، أنا مقيم في قطر', 'value' => 'yes']
                        ],
                        'validations' => ['required' => false]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            
            // QID Type (conditional on Qatar Resident)
            [
                'formFields' => [
                    'nameEn' => 'QID Type',
                    'nameAr' => 'نوع البطاقة القطرية',
                    'type' => 'select',
                    'section' => 'residency-travel',
                    'group' => 'residency-details',
                    'field_order' => 2,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'options' => [
                            ['labelEn' => 'Qatari', 'labelAr' => 'قطري', 'value' => 'qatari'],
                            ['labelEn' => 'Resident', 'labelAr' => 'مقيم', 'value' => 'resident'],
                            ['labelEn' => 'GCC', 'labelAr' => 'خليجي', 'value' => 'gcc']
                        ],
                        'placeholderEn' => 'Select QID Type',
                        'placeholderAr' => 'اختر نوع البطاقة',
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'are-you-qatar-resident', 'operator' => 'checked', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories (but conditional on checkbox)
            ],
            
            // QID Number
            [
                'formFields' => [
                    'nameEn' => 'QID Number',
                    'nameAr' => 'رقم البطاقة القطرية',
                    'type' => 'text',
                    'section' => 'residency-travel',
                    'group' => 'residency-details',
                    'field_order' => 3,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Enter QID number',
                        'placeholderAr' => 'أدخل رقم البطاقة',
                        'tooltipEn' => '• QID is required • only numeric characters are allowed',
                        'tooltipAr' => '• رقم البطاقة مطلوب • يُسمح فقط بالأرقام',
                        'validations' => [
                            'required' => true,
                            'pattern' => '^[0-9]+$',
                            'minLength' => 9,
                            'maxLength' => 11
                        ]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'are-you-qatar-resident', 'operator' => 'checked', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories (but conditional)
            ],
            
            // Work Permit Options
            [
                'formFields' => [
                    'nameEn' => 'Work Permit Options',
                    'nameAr' => 'خيارات تصريح العمل',
                    'type' => 'radio',
                    'section' => 'residency-travel',
                    'group' => 'residency-details',
                    'field_order' => 4,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'options' => [
                            ['labelEn' => 'I will not continue work with my current employer', 'labelAr' => 'لن أواصل العمل مع صاحب العمل الحالي', 'value' => 'no'],
                            ['labelEn' => 'I will continue work with my current employer', 'labelAr' => 'سأواصل العمل مع صاحب العمل الحالي', 'value' => 'yes']
                        ],
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'are-you-qatar-resident', 'operator' => 'checked', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories (but conditional)
            ],
            
            // Maintain Work Permit (conditional on Work Permit Options = yes)
            [
                'formFields' => [
                    'nameEn' => 'Maintain Work Permit',
                    'nameAr' => 'الحفاظ على تصريح العمل',
                    'type' => 'radio',
                    'section' => 'residency-travel',
                    'group' => 'residency-details',
                    'field_order' => 5,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'options' => [
                            ['labelEn' => 'Proceed with Mustaqel residency', 'labelAr' => 'المتابعة مع إقامة مستقل', 'value' => 'proceed'],
                            ['labelEn' => 'Maintain current residency', 'labelAr' => 'الحفاظ على الإقامة الحالية', 'value' => 'maintain']
                        ],
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'are-you-qatar-resident', 'operator' => 'checked', 'action' => 'show'],
                        ['field' => 'work-permit-options', 'operator' => 'equals', 'value' => 'yes', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories (but conditional)
            ],
            
            // Address In Qatar
            [
                'formFields' => [
                    'nameEn' => 'Address In Qatar',
                    'nameAr' => 'العنوان في قطر',
                    'type' => 'textarea',
                    'section' => 'residency-travel',
                    'group' => 'residency-details',
                    'field_order' => 6,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Enter your address in Qatar',
                        'placeholderAr' => 'أدخل عنوانك في قطر',
                        'tooltipEn' => '• Qatar Address is required • Only those Special Characters are allowed',
                        'tooltipAr' => '• العنوان في قطر مطلوب • يُسمح فقط بهذه الأحرف الخاصة',
                        'validations' => [
                            'required' => true,
                            'maxLength' => 500
                        ],
                        'rows' => 3
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'are-you-qatar-resident', 'operator' => 'checked', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories (but conditional)
            ],
            
            // Police Clearance (for Non-Residents)
            [
                'formFields' => [
                    'nameEn' => 'Police Clearance/Good Behavior Certificate',
                    'nameAr' => 'شهادة براءة ذمة من الشرطة / شهادة حسن السيرة والسلوك',
                    'type' => 'file',
                    'section' => 'documents',
                    'group' => 'required-documents',
                    'field_order' => 3,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
                        'maxSize' => 5120,
                        'helpTextEn' => 'Upload police clearance certificate',
                        'helpTextAr' => 'قم بتحميل شهادة براءة الذمة',
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'are-you-qatar-resident', 'operator' => 'notChecked', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'tal', 'value' => '{}', 'onshoreOffShore' => 'offshore', 'isRequired' => true],
                    ['key' => 'ent', 'value' => '{}', 'onshoreOffShore' => 'offshore', 'isRequired' => true],
                    ['key' => 'inv', 'value' => '{}', 'onshoreOffShore' => 'offshore', 'isRequired' => true],
                    ['key' => 'exe', 'value' => '{}', 'onshoreOffShore' => 'offshore', 'isRequired' => true],
                ]
            ],

            // ==================== SECTION 4: EMPLOYMENT INFORMATION ====================
            
            // Group: Employment Details (Only for tal, ent, exe AND Qatar Resident = TRUE)
            [
                'formFields' => [
                    'nameEn' => 'Current Job Title',
                    'nameAr' => 'المسمى الوظيفي الحالي',
                    'type' => 'select',
                    'section' => 'employment-education',
                    'group' => 'employment-details',
                    'field_order' => 1,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'options' => [
                            ['labelEn' => 'CEO', 'labelAr' => 'الرئيس التنفيذي', 'value' => 'ceo'],
                            ['labelEn' => 'CTO', 'labelAr' => 'مدير التكنولوجيا', 'value' => 'cto'],
                            ['labelEn' => 'CFO', 'labelAr' => 'المدير المالي', 'value' => 'cfo'],
                            ['labelEn' => 'COO', 'labelAr' => 'مدير العمليات', 'value' => 'coo'],
                            ['labelEn' => 'Director', 'labelAr' => 'مدير', 'value' => 'director'],
                            ['labelEn' => 'Manager', 'labelAr' => 'مدير قسم', 'value' => 'manager'],
                            ['labelEn' => 'Other', 'labelAr' => 'أخرى', 'value' => 'other']
                        ],
                        'placeholderEn' => 'Select job title',
                        'placeholderAr' => 'اختر المسمى الوظيفي',
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'are-you-qatar-resident', 'operator' => 'checked', 'action' => 'show'],
                        ['field' => 'category', 'operator' => 'in', 'value' => ['tal', 'ent', 'exe'], 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'tal', 'value' => '{}', 'onshoreOffShore' => 'onshore', 'isRequired' => true],
                    ['key' => 'ent', 'value' => '{}', 'onshoreOffShore' => 'onshore', 'isRequired' => true],
                    ['key' => 'exe', 'value' => '{}', 'onshoreOffShore' => 'onshore', 'isRequired' => true]
                ]
            ],
            
            // Other Current Job Title (conditional)
            [
                'formFields' => [
                    'nameEn' => 'Other Current Job Title',
                    'nameAr' => 'المسمى الوظيفي الحالي (أخرى)',
                    'type' => 'text',
                    'section' => 'employment-education',
                    'group' => 'employment-details',
                    'field_order' => 2,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Please specify job title',
                        'placeholderAr' => 'الرجاء تحديد المسمى الوظيفي',
                        'validations' => [
                            'required' => true,
                            'minLength' => 2,
                            'maxLength' => 100
                        ]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'current-job-title', 'operator' => 'equals', 'value' => 'other', 'action' => 'show'],
                        ['field' => 'are-you-qatar-resident', 'operator' => 'checked', 'action' => 'show'],
                        ['field' => 'category', 'operator' => 'in', 'value' => ['tal', 'ent', 'exe'], 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'tal', 'value' => '{}', 'onshoreOffShore' => 'onshore', 'isRequired' => true],
                    ['key' => 'ent', 'value' => '{}', 'onshoreOffShore' => 'onshore', 'isRequired' => true],
                    ['key' => 'exe', 'value' => '{}', 'onshoreOffShore' => 'onshore', 'isRequired' => true]
                ]
            ],
            
            [
                'formFields' => [
                    'nameEn' => 'Date of Joining',
                    'nameAr' => 'تاريخ الالتحاق',
                    'type' => 'date',
                    'section' => 'employment-education',
                    'group' => 'employment-details',
                    'field_order' => 3,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Select joining date',
                        'placeholderAr' => 'اختر تاريخ الالتحاق',
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'are-you-qatar-resident', 'operator' => 'checked', 'action' => 'show'],
                        ['field' => 'category', 'operator' => 'in', 'value' => ['tal', 'ent', 'exe'], 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'tal', 'value' => '{}', 'onshoreOffShore' => 'onshore', 'isRequired' => true],
                    ['key' => 'ent', 'value' => '{}', 'onshoreOffShore' => 'onshore', 'isRequired' => true],
                    ['key' => 'exe', 'value' => '{}', 'onshoreOffShore' => 'onshore', 'isRequired' => true]
                ]
            ],
            
            [
                'formFields' => [
                    'nameEn' => 'Monthly Salary',
                    'nameAr' => 'الراتب الشهري',
                    'type' => 'text',
                    'section' => 'employment-education',
                    'group' => 'employment-details',
                    'field_order' => 4,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Enter monthly salary',
                        'placeholderAr' => 'أدخل الراتب الشهري',
                        'validations' => [
                            'required' => true,
                            'pattern' => '^[0-9,]+$',
                            'minLength' => 3,
                            'maxLength' => 20
                        ]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'are-you-qatar-resident', 'operator' => 'checked', 'action' => 'show'],
                        ['field' => 'category', 'operator' => 'in', 'value' => ['tal', 'ent', 'exe'], 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'tal', 'value' => '{}', 'onshoreOffShore' => 'onshore', 'isRequired' => true],
                    ['key' => 'ent', 'value' => '{}', 'onshoreOffShore' => 'onshore', 'isRequired' => true],
                    ['key' => 'exe', 'value' => '{}', 'onshoreOffShore' => 'onshore', 'isRequired' => true]
                ]
            ],

            // ==================== SECTION 5: INVESTMENT INFORMATION ====================
            
            // Group: Investment Details (Only for Investor category)
            [
                'formFields' => [
                    'nameEn' => 'Share of the Capital',
                    'nameAr' => 'حصة رأس المال',
                    'type' => 'text',
                    'section' => 'employment-education',
                    'group' => 'investment-details',
                    'field_order' => 1,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Enter share percentage',
                        'placeholderAr' => 'أدخل نسبة المساهمة',
                        'validations' => [
                            'required' => true,
                            'pattern' => '^[0-9.]+%?$',
                            'maxLength' => 10
                        ]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'category', 'operator' => 'equals', 'value' => 'inv', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'inv', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => true]
                ]
            ],
            
            [
                'formFields' => [
                    'nameEn' => 'Amount of Capital',
                    'nameAr' => 'قيمة رأس المال',
                    'type' => 'text',
                    'section' => 'employment-education',
                    'group' => 'investment-details',
                    'field_order' => 2,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Enter amount',
                        'placeholderAr' => 'أدخل المبلغ',
                        'validations' => [
                            'required' => true,
                            'pattern' => '^[0-9,]+$',
                            'maxLength' => 20
                        ]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'category', 'operator' => 'equals', 'value' => 'inv', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'inv', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => true]
                ]
            ],
            
            // Company Name (Investor + Qatar Resident)
            [
                'formFields' => [
                    'nameEn' => 'Company Name',
                    'nameAr' => 'اسم الشركة',
                    'type' => 'text',
                    'section' => 'employment-education',
                    'group' => 'investment-details',
                    'field_order' => 3,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Enter company name',
                        'placeholderAr' => 'أدخل اسم الشركة',
                        'validations' => [
                            'required' => true,
                            'minLength' => 2,
                            'maxLength' => 200
                        ]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'category', 'operator' => 'equals', 'value' => 'inv', 'action' => 'show'],
                        ['field' => 'are-you-qatar-resident', 'operator' => 'checked', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'inv', 'value' => '{}', 'onshoreOffShore' => 'onshore', 'isRequired' => true]
                ]
            ],
            
            // Company Classification
            [
                'formFields' => [
                    'nameEn' => 'Company Classification',
                    'nameAr' => 'تصنيف الشركة',
                    'type' => 'select',
                    'section' => 'employment-education',
                    'group' => 'investment-details',
                    'field_order' => 4,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'options' => [
                            ['labelEn' => 'Public', 'labelAr' => 'عامة', 'value' => 'public'],
                            ['labelEn' => 'Private', 'labelAr' => 'خاصة', 'value' => 'private'],
                            ['labelEn' => 'Multinational', 'labelAr' => 'متعددة الجنسيات', 'value' => 'multinational'],
                            ['labelEn' => 'Family Business', 'labelAr' => 'شركة عائلية', 'value' => 'family'],
                            ['labelEn' => 'Startup', 'labelAr' => 'شركة ناشئة', 'value' => 'startup'],
                            ['labelEn' => 'Other', 'labelAr' => 'أخرى', 'value' => 'other']
                        ],
                        'placeholderEn' => 'Select company classification',
                        'placeholderAr' => 'اختر تصنيف الشركة',
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'category', 'operator' => 'equals', 'value' => 'inv', 'action' => 'show'],
                        ['field' => 'are-you-qatar-resident', 'operator' => 'checked', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'inv', 'value' => '{}', 'onshoreOffShore' => 'onshore', 'isRequired' => true]
                ]
            ],
            
            // Other Company Classification
            [
                'formFields' => [
                    'nameEn' => 'Other Company Classification',
                    'nameAr' => 'تصنيف الشركة (أخرى)',
                    'type' => 'text',
                    'section' => 'employment-education',
                    'group' => 'investment-details',
                    'field_order' => 5,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'placeholderEn' => 'Please specify classification',
                        'placeholderAr' => 'الرجاء تحديد التصنيف',
                        'validations' => [
                            'required' => true,
                            'minLength' => 2,
                            'maxLength' => 100
                        ]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'category', 'operator' => 'equals', 'value' => 'inv', 'action' => 'show'],
                        ['field' => 'are-you-qatar-resident', 'operator' => 'checked', 'action' => 'show'],
                        ['field' => 'company-classification', 'operator' => 'equals', 'value' => 'other', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'inv', 'value' => '{}', 'onshoreOffShore' => 'onshore', 'isRequired' => true]
                ]
            ],
            
            // Tax Report (for Tax Based sub-category)
            [
                'formFields' => [
                    'nameEn' => 'Tax report for the last three years',
                    'nameAr' => 'تقرير ضريبي لآخر ثلاث سنوات',
                    'type' => 'file',
                    'section' => 'documents',
                    'group' => 'investment-documents',
                    'field_order' => 1,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
                        'maxSize' => 5120,
                        'helpTextEn' => 'Upload tax reports for last 3 years',
                        'helpTextAr' => 'قم بتحميل التقارير الضريبية لآخر 3 سنوات',
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'category', 'operator' => 'equals', 'value' => 'inv', 'action' => 'show'],
                        ['field' => 'sub-category', 'operator' => 'equals', 'value' => 'tax-based', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'inv', 'value' => json_encode(['sub_category' => 'tax-based']), 'onshoreOffShore' => 'both', 'isRequired' => true]
                ]
            ],
            
            // Asset-Based Audit Report (for Assets Based sub-category)
            [
                'formFields' => [
                    'nameEn' => 'Asset-Based Audit Report',
                    'nameAr' => 'تقرير التدقيق القائم على الأصول',
                    'type' => 'file',
                    'section' => 'documents',
                    'group' => 'investment-documents',
                    'field_order' => 2,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'extensions' => ['xlsx', 'xlsb', 'xls', 'xltx', 'xlsm', 'csv'],
                        'maxSize' => 10240,
                        'helpTextEn' => 'Upload asset-based audit report',
                        'helpTextAr' => 'قم بتحميل تقرير التدقيق القائم على الأصول',
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'category', 'operator' => 'equals', 'value' => 'inv', 'action' => 'show'],
                        ['field' => 'sub-category', 'operator' => 'equals', 'value' => 'assets-based', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'inv', 'value' => json_encode(['sub_category' => 'assets-based']), 'onshoreOffShore' => 'both', 'isRequired' => true]
                ]
            ],

            // ==================== SECTION 6: SPECIAL ENTITY-BASED REQUIREMENTS ====================
            
            // Group: ICT Ministry Documents (for specific entity)
            [
                'formFields' => [
                    'nameEn' => 'Patent Certificate or International Award Certificate',
                    'nameAr' => 'شهادة براءة الاختراع أو شهادة جائزة دولية',
                    'type' => 'file',
                    'section' => 'documents',
                    'group' => 'ict-ministry-documents',
                    'field_order' => 1,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
                        'maxSize' => 5120,
                        'helpTextEn' => 'Must be attested',
                        'helpTextAr' => 'يجب أن تكون موثقة',
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'entity', 'operator' => 'equals', 'value' => 'ict-ministry', 'action' => 'show'],
                        ['field' => 'activity', 'operator' => 'equals', 'value' => 'inventors', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'tal', 'value' => json_encode(['entity' => 'ict-ministry', 'activity' => 'inventors']), 'onshoreOffShore' => 'both', 'isRequired' => true]
                ]
            ],
            
            [
                'formFields' => [
                    'nameEn' => 'Summary of the Invention (1–2 pages)',
                    'nameAr' => 'نبذة عن الإختراع (من صفحة إلى صفحتين)',
                    'type' => 'file',
                    'section' => 'documents',
                    'group' => 'ict-ministry-documents',
                    'field_order' => 2,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
                        'maxSize' => 5120,
                        'helpTextEn' => 'Upload summary of invention',
                        'helpTextAr' => 'قم بتحميل نبذة عن الاختراع',
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'entity', 'operator' => 'equals', 'value' => 'ict-ministry', 'action' => 'show'],
                        ['field' => 'activity', 'operator' => 'equals', 'value' => 'inventors', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'tal', 'value' => json_encode(['entity' => 'ict-ministry', 'activity' => 'inventors']), 'onshoreOffShore' => 'both', 'isRequired' => true]
                ]
            ],
            
            [
                'formFields' => [
                    'nameEn' => 'Recommendation Letter from ICT Authority',
                    'nameAr' => 'توصية من جهة مختصة في تكنولوجيا المعلومات والاتصالات',
                    'type' => 'file',
                    'section' => 'documents',
                    'group' => 'ict-ministry-documents',
                    'field_order' => 3,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
                        'maxSize' => 5120,
                        'helpTextEn' => 'Upload recommendation letter',
                        'helpTextAr' => 'قم بتحميل خطاب التوصية',
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'entity', 'operator' => 'equals', 'value' => 'ict-ministry', 'action' => 'show'],
                        ['field' => 'activity', 'operator' => 'equals', 'value' => 'specialized-experts', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'tal', 'value' => json_encode(['entity' => 'ict-ministry', 'activity' => 'specialized-experts']), 'onshoreOffShore' => 'both', 'isRequired' => true]
                ]
            ],
            
            [
                'formFields' => [
                    'nameEn' => 'Proof of University Ranking',
                    'nameAr' => 'إثبات تصنيف الجامعة',
                    'type' => 'file',
                    'section' => 'documents',
                    'group' => 'ict-ministry-documents',
                    'field_order' => 4,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
                        'maxSize' => 5120,
                        'helpTextEn' => 'Screenshot or official document',
                        'helpTextAr' => 'لقطة شاشة أو مستند رسمي',
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'entity', 'operator' => 'equals', 'value' => 'ict-ministry', 'action' => 'show'],
                        ['field' => 'activity', 'operator' => 'equals', 'value' => 'digital-industry-leaders', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'tal', 'value' => json_encode(['entity' => 'ict-ministry', 'activity' => 'digital-industry-leaders']), 'onshoreOffShore' => 'both', 'isRequired' => true]
                ]
            ],
            
            [
                'formFields' => [
                    'nameEn' => 'Valid Employment Contract',
                    'nameAr' => 'عقد عمل ساري المفعول',
                    'type' => 'file',
                    'section' => 'documents',
                    'group' => 'ict-ministry-documents',
                    'field_order' => 5,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
                        'maxSize' => 5120,
                        'helpTextEn' => 'Must show role & salary and be attested',
                        'helpTextAr' => 'يجب أن يوضح الوظيفة والراتب وأن يكون موثقًا',
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'entity', 'operator' => 'equals', 'value' => 'ict-ministry', 'action' => 'show'],
                        ['field' => 'activity', 'operator' => 'equals', 'value' => 'digital-industry-leaders', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'tal', 'value' => json_encode(['entity' => 'ict-ministry', 'activity' => 'digital-industry-leaders']), 'onshoreOffShore' => 'both', 'isRequired' => true]
                ]
            ],

            // ==================== SECTION 7: CATEGORY-BASED DOCUMENTS ====================
            
            // Group: Required Documents
            // Personal Photo (All categories)
            [
                'formFields' => [
                    'nameEn' => 'Personal Photo',
                    'nameAr' => 'صورة شخصية',
                    'type' => 'file',
                    'section' => 'documents',
                    'group' => 'required-documents',
                    'field_order' => 1,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'extensions' => ['jpg', 'jpeg', 'png'],
                        'maxSize' => 2048,
                        'helpTextEn' => 'Upload a recent passport-sized photo',
                        'helpTextAr' => 'قم بتحميل صورة حديثة بحجم جواز السفر',
                        'validations' => ['required' => true]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            
            // Passport Copy (All categories)
            [
                'formFields' => [
                    'nameEn' => 'Passport Copy',
                    'nameAr' => 'نسخة من جواز السفر',
                    'type' => 'file',
                    'section' => 'documents',
                    'group' => 'required-documents',
                    'field_order' => 2,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
                        'maxSize' => 5120,
                        'helpTextEn' => 'Upload a clear copy of your passport',
                        'helpTextAr' => 'قم بتحميل نسخة واضحة من جواز سفرك',
                        'validations' => ['required' => true]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            
            // CV with Experience Letter (All categories)
            [
                'formFields' => [
                    'nameEn' => 'CV with Experience letter',
                    'nameAr' => 'السيرة الذاتية مع شهادة الخبرة',
                    'type' => 'file',
                    'section' => 'documents',
                    'group' => 'required-documents',
                    'field_order' => 3,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
                        'maxSize' => 5120,
                        'helpTextEn' => 'Upload your CV and experience letters',
                        'helpTextAr' => 'قم بتحميل سيرتك الذاتية وشهادات الخبرة',
                        'validations' => ['required' => true]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
            
            // Certified Academic Degree (Talent and Executive only)
            [
                'formFields' => [
                    'nameEn' => 'Certified Academic Degree',
                    'nameAr' => 'الشهادة أو الشهادات التعليمية',
                    'type' => 'file',
                    'section' => 'documents',
                    'group' => 'required-documents',
                    'field_order' => 4,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
                        'maxSize' => 5120,
                        'helpTextEn' => 'Upload your academic certificates',
                        'helpTextAr' => 'قم بتحميل شهاداتك التعليمية',
                        'validations' => ['required' => true]
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'tal', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => true],
                    ['key' => 'exe', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => true]
                ]
            ],
            
            // 3 Months Bank Statement (Entrepreneur, Investor, Executive)
            [
                'formFields' => [
                    'nameEn' => '3 Months Bank Statement',
                    'nameAr' => 'كشف حساب بنكي شخصي',
                    'type' => 'file',
                    'section' => 'documents',
                    'group' => 'required-documents',
                    'field_order' => 5,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
                        'maxSize' => 5120,
                        'helpTextEn' => 'Upload last 3 months bank statement',
                        'helpTextAr' => 'قم بتحميل كشف حساب بنكي لآخر 3 أشهر',
                        'validations' => ['required' => true]
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'ent', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => true],
                    ['key' => 'inv', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => true],
                    ['key' => 'exe', 'value' => '{}', 'onshoreOffShore' => 'both', 'isRequired' => true]
                ]
            ],
            
            // QID Copy (for Qatar residents - all categories)
            [
                'formFields' => [
                    'nameEn' => 'QID Copy',
                    'nameAr' => 'نسخة من البطاقة القطرية',
                    'type' => 'file',
                    'section' => 'documents',
                    'group' => 'residency-documents',
                    'field_order' => 1,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
                        'maxSize' => 5120,
                        'helpTextEn' => 'For Qatar residents',
                        'helpTextAr' => 'للمقيمين في قطر',
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'are-you-qatar-resident', 'operator' => 'checked', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories (but conditional)
            ],
            
            // Establishment Card/QID of Sponsor
            [
                'formFields' => [
                    'nameEn' => 'Establishment Card/QID of the Sponsor',
                    'nameAr' => 'بطاقة قيد المنشأة / البطاقة الشخصية للكفيل',
                    'type' => 'file',
                    'section' => 'documents',
                    'group' => 'residency-documents',
                    'field_order' => 2,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
                        'maxSize' => 5120,
                        'helpTextEn' => 'Upload sponsor document',
                        'helpTextAr' => 'قم بتحميل مستند الكفيل',
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'are-you-qatar-resident', 'operator' => 'checked', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories (but conditional)
            ],
            
            // Valid Commercial Registration (CR)
            [
                'formFields' => [
                    'nameEn' => 'Valid Commercial Registration (CR)',
                    'nameAr' => 'سجل تجاري ساري المفعول',
                    'type' => 'file',
                    'section' => 'documents',
                    'group' => 'residency-documents',
                    'field_order' => 3,
                    'grid_columns' => 4,
                    'repeatable' => false,
                    'meta' => json_encode([
                        'extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
                        'maxSize' => 5120,
                        'helpTextEn' => 'Upload valid commercial registration',
                        'helpTextAr' => 'قم بتحميل السجل التجاري الساري',
                        'validations' => ['required' => true]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'are-you-qatar-resident', 'operator' => 'checked', 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [
                    ['key' => 'inv', 'value' => '{}', 'onshoreOffShore' => 'onshore', 'isRequired' => true]
                ]
            ],

            // ==================== REPEATABLE GROUPS ====================
            
            // Group: Residences (Repeatable)
            [
                'formFields' => [
                    'nameEn' => 'Active Residencies in Other Countries',
                    'nameAr' => 'الإقامات النشطة في دول أخرى',
                    'type' => 'group',
                    'section' => 'residency-travel',
                    'group' => 'residences',
                    'field_order' => 1,
                    'grid_columns' => 12,
                    'repeatable' => true,
                    'repeatable_label' => 'Add Residence',
                    'repeatable_max' => 10,
                    'meta' => json_encode([
                        'fields' => [
                            [
                                'nameEn' => 'Country',
                                'nameAr' => 'البلد',
                                'type' => 'select',
                                'grid_columns' => 4,
                                'options_from' => 'nationalities',
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Type Of Residency',
                                'nameAr' => 'نوع الإقامة',
                                'type' => 'text',
                                'grid_columns' => 4,
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Issue Date',
                                'nameAr' => 'تاريخ الإصدار',
                                'type' => 'date',
                                'grid_columns' => 4,
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Expiry Date',
                                'nameAr' => 'تاريخ الانتهاء',
                                'type' => 'date',
                                'grid_columns' => 4,
                                'validations' => ['required' => true]
                            ]
                        ]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],

            // Group: Other Nationalities (Repeatable)
            [
                'formFields' => [
                    'nameEn' => 'Other Nationalities',
                    'nameAr' => 'الجنسيات الأخرى',
                    'type' => 'group',
                    'section' => 'residency-travel',
                    'group' => 'other-nationalities',
                    'field_order' => 2,
                    'grid_columns' => 12,
                    'repeatable' => true,
                    'repeatable_label' => 'Add Nationality',
                    'repeatable_max' => 5,
                    'meta' => json_encode([
                        'fields' => [
                            [
                                'nameEn' => 'Country',
                                'nameAr' => 'البلد',
                                'type' => 'select',
                                'grid_columns' => 4,
                                'options_from' => 'nationalities',
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Passport Number',
                                'nameAr' => 'رقم جواز السفر',
                                'type' => 'text',
                                'grid_columns' => 4,
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Date Of Issue',
                                'nameAr' => 'تاريخ الإصدار',
                                'type' => 'date',
                                'grid_columns' => 4,
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Expiry Date',
                                'nameAr' => 'تاريخ الانتهاء',
                                'type' => 'date',
                                'grid_columns' => 4,
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Place Of Issue',
                                'nameAr' => 'مكان الإصدار',
                                'type' => 'text',
                                'grid_columns' => 4,
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Active / Previous',
                                'nameAr' => 'نشط / سابق',
                                'type' => 'select',
                                'grid_columns' => 4,
                                'options' => [
                                    ['labelEn' => 'Active', 'labelAr' => 'نشط', 'value' => 'active'],
                                    ['labelEn' => 'Previous', 'labelAr' => 'سابق', 'value' => 'previous']
                                ],
                                'validations' => ['required' => true]
                            ]
                        ]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],

            // Group: Countries Visited (Repeatable)
            [
                'formFields' => [
                    'nameEn' => 'Countries Visited In The Last 10 Years',
                    'nameAr' => 'الدول التي تمت زيارتها في آخر 10 سنوات',
                    'type' => 'group',
                    'section' => 'residency-travel',
                    'group' => 'countries-visited',
                    'field_order' => 3,
                    'grid_columns' => 12,
                    'repeatable' => true,
                    'repeatable_label' => 'Add Country',
                    'repeatable_max' => 20,
                    'meta' => json_encode([
                        'fields' => [
                            [
                                'nameEn' => 'Country',
                                'nameAr' => 'البلد',
                                'type' => 'select',
                                'grid_columns' => 4,
                                'options_from' => 'nationalities',
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Period',
                                'nameAr' => 'المدة',
                                'type' => 'select',
                                'grid_columns' => 4,
                                'options' => [
                                    ['labelEn' => 'Less than 1 week', 'labelAr' => 'أقل من أسبوع', 'value' => '<1w'],
                                    ['labelEn' => '1-2 weeks', 'labelAr' => '1-2 أسبوع', 'value' => '1-2w'],
                                    ['labelEn' => '2-4 weeks', 'labelAr' => '2-4 أسابيع', 'value' => '2-4w'],
                                    ['labelEn' => '1-3 months', 'labelAr' => '1-3 أشهر', 'value' => '1-3m'],
                                    ['labelEn' => '3-6 months', 'labelAr' => '3-6 أشهر', 'value' => '3-6m'],
                                    ['labelEn' => '6-12 months', 'labelAr' => '6-12 شهر', 'value' => '6-12m'],
                                    ['labelEn' => 'More than 1 year', 'labelAr' => 'أكثر من سنة', 'value' => '>1y']
                                ],
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Reason of Visit',
                                'nameAr' => 'سبب الزيارة',
                                'type' => 'select',
                                'grid_columns' => 4,
                                'options' => [
                                    ['labelEn' => 'Tourism', 'labelAr' => 'سياحة', 'value' => 'tourism'],
                                    ['labelEn' => 'Business', 'labelAr' => 'أعمال', 'value' => 'business'],
                                    ['labelEn' => 'Education', 'labelAr' => 'تعليم', 'value' => 'education'],
                                    ['labelEn' => 'Medical', 'labelAr' => 'علاج', 'value' => 'medical'],
                                    ['labelEn' => 'Conference', 'labelAr' => 'مؤتمر', 'value' => 'conference'],
                                    ['labelEn' => 'Training', 'labelAr' => 'تدريب', 'value' => 'training'],
                                    ['labelEn' => 'Family Visit', 'labelAr' => 'زيارة عائلية', 'value' => 'family'],
                                    ['labelEn' => 'Other', 'labelAr' => 'أخرى', 'value' => 'other']
                                ],
                                'validations' => ['required' => true]
                            ]
                        ]
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],

            // Group: Family Members (Repeatable - conditional on marital status)
            [
                'formFields' => [
                    'nameEn' => 'Family Members',
                    'nameAr' => 'أفراد العائلة',
                    'type' => 'group',
                    'section' => 'residency-travel',
                    'group' => 'family-members',
                    'field_order' => 4,
                    'grid_columns' => 12,
                    'repeatable' => true,
                    'repeatable_label' => 'Add Family Member',
                    'repeatable_max' => 20,
                    'meta' => json_encode([
                        'fields' => [
                            [
                                'nameEn' => 'Name',
                                'nameAr' => 'الاسم',
                                'type' => 'text',
                                'grid_columns' => 4,
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Relationship',
                                'nameAr' => 'العلاقة',
                                'type' => 'select',
                                'grid_columns' => 4,
                                'options' => [
                                    ['labelEn' => 'Spouse', 'labelAr' => 'زوج/زوجة', 'value' => 'spouse'],
                                    ['labelEn' => 'Son', 'labelAr' => 'ابن', 'value' => 'son'],
                                    ['labelEn' => 'Daughter', 'labelAr' => 'ابنة', 'value' => 'daughter'],
                                    ['labelEn' => 'Father', 'labelAr' => 'أب', 'value' => 'father'],
                                    ['labelEn' => 'Mother', 'labelAr' => 'أم', 'value' => 'mother'],
                                    ['labelEn' => 'Brother', 'labelAr' => 'أخ', 'value' => 'brother'],
                                    ['labelEn' => 'Sister', 'labelAr' => 'أخت', 'value' => 'sister']
                                ],
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Date of Birth',
                                'nameAr' => 'تاريخ الميلاد',
                                'type' => 'date',
                                'grid_columns' => 4,
                                'validations' => ['required' => true]
                            ],
                            [
                                'nameEn' => 'Occupation',
                                'nameAr' => 'المهنة',
                                'type' => 'text',
                                'grid_columns' => 4,
                                'placeholderEn' => 'Current occupation',
                                'placeholderAr' => 'المهنة الحالية',
                                'validations' => ['required' => false]
                            ]
                        ]
                    ]),
                    'conditions' => json_encode([
                        ['field' => 'marital-status', 'operator' => 'notIn', 'value' => ['single'], 'action' => 'show']
                    ]),
                    'status' => 1
                ],
                'metas' => [] // All categories
            ],
        ];

        foreach ($formFields as $data) {
            // Create or update form field
            $fieldData = $data['formFields'];
            
            // Generate slug if not present
            if (!isset($fieldData['slug'])) {
                $fieldData['slug'] = Str::slug($fieldData['nameEn']);
            }
            
            // Check if field exists by slug or nameEn
            $field = FormFields::withTrashed()->updateOrCreate(
                ['slug' => $fieldData['slug']],
                $fieldData
            );
            
            // Restore if trashed
            if ($field->trashed()) {
                $field->restore();
            }
            
            // Handle metas
            if (isset($data['metas'])) {
                foreach ($data['metas'] as $metaData) {
                    // Ensure value is JSON string
                    if (is_array($metaData['value'])) {
                        $metaData['value'] = json_encode($metaData['value'], JSON_UNESCAPED_UNICODE);
                    }
                    
                    $meta = FormFieldMeta::withTrashed()->updateOrCreate(
                        [
                            'ffId' => $field->id,
                            'key' => $metaData['key']
                        ],
                        [
                            'value' => $metaData['value'],
                            'onshoreOffShore' => $metaData['onshoreOffShore'],
                            'isRequired' => $metaData['isRequired']
                        ]
                    );
                    
                    if ($meta->trashed()) {
                        $meta->restore();
                    }
                }
            } else {
                // If no metas, ensure any existing metas are deleted
                FormFieldMeta::where('ffId', $field->id)->delete();
            }
        }
        
        $this->command->info('Form fields seeded successfully!');
    }
}