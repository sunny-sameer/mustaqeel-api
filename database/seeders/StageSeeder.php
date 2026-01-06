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
                'id'=>1,
                'name'=>'Application',
                'nameAr'=>'مقدم الطلب',
                'order'=>7
            ],
            [
                'id'=>2,
                'name'=>'Jusour',
                'nameAr'=>'جسور',
                'order'=>1
            ],
            [
                'id'=>3,
                'name'=>'Entity',
                'nameAr'=>'الجهة',
                'order'=>2
            ],
            [
                'id'=>4,
                'name'=>'MOCI',
                'nameAr'=>'وزارة التجارة والصناعة',
                'order'=>3
            ],
            [
                'id'=>5,
                'name'=>'VFS',
                'nameAr'=>'خدمات تسهيل التأشيرة',
                'order'=>4
            ],
            [
                'id'=>6,
                'name'=>'MOL',
                'nameAr'=>'وزارة العمل',
                'order'=>5
            ],
            [
                'id'=>7,
                'name'=>'Hayya',
                'nameAr'=>'هيا',
                'order'=>6
            ],
        ];

        foreach ($stages as $key => $stage) {
            Stages::updateOrCreate(['id'=>$stage['id']],$stage);
        }

        $stageStatuses = [
            [
                'stageId'=>1,
                'name'=>'Draft',
                'nameAr'=>'المسودة'
            ],
            [
                'stageId'=>1,
                'name'=>'Under Review',
                'nameAr'=>'قيد المراجعة'
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
                'stageId'=>1,
                'name'=>'Cancelled',
                'nameAr'=>'تم الإلغاء'
            ],
            [
                'stageId'=>2,
                'name'=>'Under Review',
                'nameAr'=>'قيد المراجعة'
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
                'stageId'=>2,
                'name'=>'Cancelled',
                'nameAr'=>'تم الإلغاء'
            ],
            [
                'stageId'=>3,
                'name'=>'Under Review',
                'nameAr'=>'قيد المراجعة'
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
                'name'=>'Under Review',
                'nameAr'=>'قيد المراجعة'
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
                'name'=>'Under Review',
                'nameAr'=>'قيد المراجعة'
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
                'name'=>'Under Review',
                'nameAr'=>'قيد المراجعة'
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
                'name'=>'Under Review',
                'nameAr'=>'قيد المراجعة'
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
            StagesStatuses::updateOrCreate(['stageId'=>$stageStatus['stageId'],'name'=>$stageStatus['name']],$stageStatus);
        }
    }
}
