<?php

namespace Database\Seeders;

use App\Models\Stages;
use App\Models\StagesStatuses;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stages = [
            [
                'name'=>'Application',
                'nameAr'=>'مقدم الطلب',
            ],
            [
                'name'=>'Jusour',
                'nameAr'=>'جسور',
            ],
            [
                'name'=>'Entity',
                'nameAr'=>'الجهة',
            ],
            [
                'name'=>'MOCI',
                'nameAr'=>'وزارة التجارة والصناعة',
            ],
            [
                'name'=>'VFS',
                'nameAr'=>'خدمات تسهيل التأشيرة',
            ],
            [
                'name'=>'MOL',
                'nameAr'=>'وزارة العمل',
            ],
            [
                'name'=>'Hayya',
                'nameAr'=>'هيا',
            ],
        ];

        foreach ($stages as $key => $stage) {
            Stages::firstOrCreate($stage);
        }

        $stageStatuses = [
            [
                'stageId'=>1,
                'name'=>'Draft',
                'nameAr'=>'المسودة'
            ],
            [
                'stageId'=>1,
                'name'=>'Pending',
                'nameAr'=>'معلق'
            ],
            [
                'stageId'=>1,
                'name'=>'Approved',
                'nameAr'=>'قبلت'
            ],
            [
                'stageId'=>1,
                'name'=>'Rejected',
                'nameAr'=>'مرفوض'
            ],
            [
                'stageId'=>1,
                'name'=>'Additional Documents Requested',
                'nameAr'=>'مستندات إضافية مطلوبة'
            ],
            [
                'stageId'=>1,
                'name'=>'Additional Documents Submitted',
                'nameAr'=>'تم تقديم المستندات الإضافية'
            ],
            [
                'stageId'=>1,
                'name'=>'Reupload Documents Requested',
                'nameAr'=>'طلب إعادة تحميل مستند'
            ],
            [
                'stageId'=>1,
                'name'=>'Reupload Documents Submitted',
                'nameAr'=>'تم تقديم إعادة تحميل مستند'
            ],
            [
                'stageId'=>2,
                'name'=>'Pending',
                'nameAr'=>'معلق'
            ],
            [
                'stageId'=>2,
                'name'=>'On Hold',
                'nameAr'=>'قيد الانتظار'
            ],
            [
                'stageId'=>2,
                'name'=>'Approved',
                'nameAr'=>'معتمد'
            ],
            [
                'stageId'=>2,
                'name'=>'Rejected',
                'nameAr'=>'مرفوض'
            ],
            [
                'stageId'=>2,
                'name'=>'Reupload Documents Requested',
                'nameAr'=>'طلب إعادة تحميل مستند'
            ],
            [
                'stageId'=>2,
                'name'=>'Reupload Documents Submitted',
                'nameAr'=>'تم تقديم إعادة تحميل مستند'
            ],
            [
                'stageId'=>3,
                'name'=>'Pending',
                'nameAr'=>'معلق'
            ],
            [
                'stageId'=>3,
                'name'=>'Approved',
                'nameAr'=>'قبلت'
            ],
            [
                'stageId'=>3,
                'name'=>'Rejected',
                'nameAr'=>'مرفوض'
            ],
            [
                'stageId'=>3,
                'name'=>'Additional Documents Requested',
                'nameAr'=>'مستندات إضافية مطلوبة'
            ],
            [
                'stageId'=>3,
                'name'=>'Additional Documents Submitted',
                'nameAr'=>'تم تقديم المستندات الإضافية'
            ],
            [
                'stageId'=>4,
                'name'=>'Pending',
                'nameAr'=>'معلق'
            ],
            [
                'stageId'=>4,
                'name'=>'Approved',
                'nameAr'=>'معتمد'
            ],
            [
                'stageId'=>4,
                'name'=>'Rejected',
                'nameAr'=>'مرفوض'
            ],
            [
                'stageId'=>4,
                'name'=>'Additional Documents Requested',
                'nameAr'=>'مستندات إضافية مطلوبة'
            ],
            [
                'stageId'=>4,
                'name'=>'Additional Documents Submitted',
                'nameAr'=>'تم تقديم المستندات الإضافية'
            ],
            [
                'stageId'=>5,
                'name'=>'Pending',
                'nameAr'=>'معلق'
            ],
            [
                'stageId'=>5,
                'name'=>'Approved',
                'nameAr'=>'معتمد'
            ],
            [
                'stageId'=>5,
                'name'=>'Rejected',
                'nameAr'=>'مرفوض'
            ],
            [
                'stageId'=>6,
                'name'=>'Pending',
                'nameAr'=>'معلق'
            ],
            [
                'stageId'=>6,
                'name'=>'Approved',
                'nameAr'=>'معتمد'
            ],
            [
                'stageId'=>6,
                'name'=>'Rejected',
                'nameAr'=>'مرفوض'
            ],
            [
                'stageId'=>7,
                'name'=>'Pending',
                'nameAr'=>'معلق'
            ],
            [
                'stageId'=>7,
                'name'=>'Approved',
                'nameAr'=>'معتمد'
            ],
            [
                'stageId'=>7,
                'name'=>'Rejected',
                'nameAr'=>'مرفوض'
            ],
        ];

        foreach ($stageStatuses as $key => $stageStatus) {
            StagesStatuses::firstOrCreate($stageStatus);
        }
    }
}
