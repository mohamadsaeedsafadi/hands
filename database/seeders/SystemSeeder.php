<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\ServiceCategory;
use App\Models\ServiceQuestion;
class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@app.com',
            'password' => Hash::make('Superadmin@123'),
            'role' => 'super_admin'
        ]);
        /*
|--------------------------------------------------------------------------
| 1 HOME SERVICES
|--------------------------------------------------------------------------
*/

$home = ServiceCategory::create([
'name' => 'خدمات منزلية',
'parent_id' => null
]);

$cleaning = ServiceCategory::create([
'name' => 'تنظيف',
'parent_id' => $home->id
]);

$plumbing = ServiceCategory::create([
'name' => 'سباكة',
'parent_id' => $home->id
]);

$electric = ServiceCategory::create([
'name' => 'كهرباء',
'parent_id' => $home->id
]);

$appliances = ServiceCategory::create([
'name' => 'صيانة أجهزة',
'parent_id' => $home->id
]);

$paint = ServiceCategory::create([
'name' => 'دهان',
'parent_id' => $home->id
]);

$carpentry = ServiceCategory::create([
'name' => 'نجارة',
'parent_id' => $home->id
]);

$this->questionsCleaning($cleaning->id);
$this->questionsPlumbing($plumbing->id);
$this->questionsElectric($electric->id);
$this->questionsAppliance($appliances->id);
$this->questionsPaint($paint->id);
$this->questionsCarpentry($carpentry->id);


/*
|--------------------------------------------------------------------------
| 2 CAR SERVICES
|--------------------------------------------------------------------------
*/

$car = ServiceCategory::create([
'name' => 'خدمات سيارات',
'parent_id' => null
]);

$mechanic = ServiceCategory::create([
'name' => 'ميكانيك',
'parent_id' => $car->id
]);

$wash = ServiceCategory::create([
'name' => 'غسيل سيارات',
'parent_id' => $car->id
]);

$this->questionsMechanic($mechanic->id);
$this->questionsCarWash($wash->id);


/*
|--------------------------------------------------------------------------
| 3 TECH SERVICES
|--------------------------------------------------------------------------
*/

$tech = ServiceCategory::create([
'name'=>'خدمات تقنية',
'parent_id'=>null
]);

$screen = ServiceCategory::create([
'name'=>'تركيب شاشات',
'parent_id'=>$tech->id
]);

$camera = ServiceCategory::create([
'name'=>'تركيب كاميرات',
'parent_id'=>$tech->id
]);

$programming = ServiceCategory::create([
'name'=>'برمجة وتصميم مواقع',
'parent_id'=>$tech->id
]);

$this->questionsScreen($screen->id);
$this->questionsCamera($camera->id);
$this->questionsProgramming($programming->id);


/*
|--------------------------------------------------------------------------
| 4 EDUCATION
|--------------------------------------------------------------------------
*/

$edu = ServiceCategory::create([
'name'=>'خدمات تعليمية',
'parent_id'=>null
]);

$tutor = ServiceCategory::create([
'name'=>'مدرس خصوصي',
'parent_id'=>$edu->id
]);

$translate = ServiceCategory::create([
'name'=>'ترجمة',
'parent_id'=>$edu->id
]);

$this->questionsTutor($tutor->id);
$this->questionsTranslation($translate->id);


/*
|--------------------------------------------------------------------------
| 5 HEALTH
|--------------------------------------------------------------------------
*/

$health = ServiceCategory::create([
'name'=>'خدمات صحية وجمالية',
'parent_id'=>null
]);

$nurse = ServiceCategory::create([
'name'=>'تمريض',
'parent_id'=>$health->id
]);

$barber = ServiceCategory::create([
'name'=>'حلاقة',
'parent_id'=>$health->id
]);

$makeup = ServiceCategory::create([
'name'=>'ميك اب',
'parent_id'=>$health->id
]);

$this->questionsNurse($nurse->id);
$this->questionsBarber($barber->id);
$this->questionsMakeup($makeup->id);


/*
|--------------------------------------------------------------------------
| 6 EVENTS
|--------------------------------------------------------------------------
*/

$event = ServiceCategory::create([
'name'=>'مناسبات',
'parent_id'=>null
]);

$photo = ServiceCategory::create([
'name'=>'تصوير',
'parent_id'=>$event->id
]);

$organize = ServiceCategory::create([
'name'=>'تنظيم حفلات',
'parent_id'=>$event->id
]);

$this->questionsPhotography($photo->id);
$this->questionsEvent($organize->id);


/*
|--------------------------------------------------------------------------
| 7 LOGISTICS
|--------------------------------------------------------------------------
*/

$log = ServiceCategory::create([
'name'=>'خدمات لوجستية',
'parent_id'=>null
]);

$delivery = ServiceCategory::create([
'name'=>'توصيل طلبات',
'parent_id'=>$log->id
]);

$moving = ServiceCategory::create([
'name'=>'نقل عفش',
'parent_id'=>$log->id
]);

$this->questionsDelivery($delivery->id);
$this->questionsMoving($moving->id);


/*
|--------------------------------------------------------------------------
| 8 OTHER
|--------------------------------------------------------------------------
*/

$other = ServiceCategory::create([
'name'=>'أخرى',
'parent_id'=>null
]);

$guard = ServiceCategory::create([
'name'=>'حراسة',
'parent_id'=>$other->id
]);

$pet = ServiceCategory::create([
'name'=>'رعاية حيوان',
'parent_id'=>$other->id
]);

$tailor = ServiceCategory::create([
'name'=>'خياطة',
'parent_id'=>$other->id
]);

$this->questionsGuard($guard->id);
$this->questionsPet($pet->id);
$this->questionsTailor($tailor->id);

}

/*
|--------------------------------------------------------------------------
| CLEANING QUESTIONS
|--------------------------------------------------------------------------
*/

private function questionsCleaning($id)
{
    $questions = [

        [
            'question' => 'ما نوع المكان المطلوب تنظيفه؟',
            'type' => 'select',
            'options' => ['شقة', 'فيلا', 'مكتب', 'محل'],
        ],
        [
            'question' => 'كم عدد الغرف؟',
            'type' => 'number',
        ],
        [
            'question' => 'هل يوجد مطبخ؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'كم عدد الحمامات؟',
            'type' => 'number',
        ],
        [
            'question' => 'ما الخدمات المطلوبة؟',
            'type' => 'multi_select',
            'options' => ['تنظيف أرضيات', 'تنظيف نوافذ', 'تنظيف سجاد', 'تنظيف مطبخ'],
        ],
        [
            'question' => 'ما مستوى الاتساخ؟',
            'type' => 'select',
            'options' => ['خفيف', 'متوسط', 'شديد'],
        ],
        [
            'question' => 'ما المساحة التقريبية (بالمتر)؟',
            'type' => 'number',
        ],
        [
            'question' => 'هل لديك ملاحظات إضافية؟',
            'type' => 'text',
        ],
        [
            'question' => 'أرفق صور للمكان (اختياري)',
            'type' => 'image',
            'is_required' => false,
        ],

    ];

    foreach ($questions as $q) {
        ServiceQuestion::create([
            'category_id' => $id,
            'question' => $q['question'],
            'type' => $q['type'],
            'options' => $q['options'] ?? null,
            'is_required' => $q['is_required'] ?? true,
        ]);
    }
}

/*
|--------------------------------------------------------------------------
| PLUMBING QUESTIONS
|--------------------------------------------------------------------------
*/

private function questionsPlumbing($id)
{
    $questions = [

        [
            'question' => 'ما نوع المشكلة؟',
            'type' => 'select',
            'options' => ['تسريب', 'انسداد', 'تركيب', 'أخرى'],
        ],
        [
            'question' => 'أين موقع المشكلة؟',
            'type' => 'select',
            'options' => ['مطبخ', 'حمام', 'حديقة'],
        ],
        [
            'question' => 'هل المشكلة طارئة؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'منذ متى بدأت المشكلة؟',
            'type' => 'text',
        ],
        [
            'question' => 'هل لديك صور للمشكلة؟',
            'type' => 'image',
            'is_required' => false,
        ],

    ];

    foreach ($questions as $q) {
        ServiceQuestion::create([
            'category_id' => $id,
            'question' => $q['question'],
            'type' => $q['type'],
            'options' => $q['options'] ?? null,
            'is_required' => $q['is_required'] ?? true,
        ]);
    }
}

/*
|--------------------------------------------------------------------------
| MECHANIC QUESTIONS
|--------------------------------------------------------------------------
*/

private function questionsMechanic($id)
{
    $questions = [

        [
            'question' => 'ما نوع السيارة؟',
            'type' => 'text',
        ],
        [
            'question' => 'ما موديل السيارة؟',
            'type' => 'number',
        ],
        [
            'question' => 'ما نوع المشكلة؟',
            'type' => 'select',
            'options' => ['محرك', 'كهرباء', 'فرامل', 'تكييف', 'أخرى'],
        ],
        [
            'question' => 'هل السيارة تعمل؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'هل يوجد أعراض؟',
            'type' => 'multi_select',
            'options' => ['صوت غريب', 'اهتزاز', 'دخان', 'تسريب سوائل'],
        ],
        [
            'question' => 'هل تحتاج سحب السيارة؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'متى ظهرت المشكلة؟',
            'type' => 'text',
        ],
        [
            'question' => 'حدد موقع السيارة',
            'type' => 'text',
        ],
        [
            'question' => 'أرفق صور أو فيديو للمشكلة',
            'type' => 'image',
            'is_required' => false,
        ],

    ];

    foreach ($questions as $q) {
        ServiceQuestion::create([
            'category_id' => $id,
            'question' => $q['question'],
            'type' => $q['type'],
            'options' => $q['options'] ?? null,
            'is_required' => $q['is_required'] ?? true,
        ]);
    }
}
   private function questionsElectric($id)
{
    $questions = [

        [
            'question' => 'ما نوع المشكلة الكهربائية؟',
            'type' => 'select',
            'options' => ['انقطاع كهرباء', 'تماس كهربائي', 'مشكلة إنارة', 'مشكلة مقابس'],
        ],
        [
            'question' => 'هل الانقطاع كامل أم جزئي؟',
            'type' => 'select',
            'options' => ['كامل', 'جزئي'],
        ],
        [
            'question' => 'مكان المشكلة',
            'type' => 'select',
            'options' => ['غرفة', 'مطبخ', 'حمام', 'خارج المنزل'],
        ],
        [
            'question' => 'كم عدد النقاط المتضررة؟',
            'type' => 'number',
        ],
        [
            'question' => 'هل لديك القطع المطلوبة؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'هل الحالة طارئة؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'صف المشكلة بالتفصيل',
            'type' => 'text',
        ],
        [
            'question' => 'أرفق صورة للمشكلة',
            'type' => 'image',
            'is_required' => false,
        ],

    ];

    foreach ($questions as $q) {
        ServiceQuestion::create([
            'category_id' => $id,
            'question' => $q['question'],
            'type' => $q['type'],
            'options' => $q['options'] ?? null,
            'is_required' => $q['is_required'] ?? true,
        ]);
    }
}
private function questionsPaint($id)
{
    $questions = [

        [
            'question' => 'ما نوع المكان؟',
            'type' => 'select',
            'options' => ['شقة', 'فيلا', 'مكتب'],
        ],
        [
            'question' => 'كم عدد الغرف؟',
            'type' => 'number',
        ],
        [
            'question' => 'ما المساحة التقريبية بالمتر؟',
            'type' => 'number',
        ],
        [
            'question' => 'ما نوع الدهان المطلوب؟',
            'type' => 'select',
            'options' => ['داخلي', 'خارجي'],
        ],
        [
            'question' => 'ما الألوان المطلوبة؟',
            'type' => 'multi_select',
            'options' => ['أبيض', 'بيج', 'رمادي', 'ألوان خاصة'],
        ],
        [
            'question' => 'هل الجدران تحتاج معجون؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'هل يوجد دهان قديم يجب إزالته؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'هل توفر الطلاء؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'ملاحظات إضافية',
            'type' => 'text',
            'is_required' => false,
        ],
        [
            'question' => 'أرفق صور للمكان',
            'type' => 'image',
            'is_required' => false,
        ],

    ];

    foreach ($questions as $q) {
        ServiceQuestion::create([
            'category_id' => $id,
            'question' => $q['question'],
            'type' => $q['type'],
            'options' => $q['options'] ?? null,
            'is_required' => $q['is_required'] ?? true,
        ]);
    }
}
private function questionsCarpentry($id)
{
    $questions = [

        [
            'question' => 'ما نوع العمل المطلوب؟',
            'type' => 'select',
            'options' => ['تصميم جديد', 'إصلاح', 'تعديل'],
        ],
        [
            'question' => 'ما نوع القطعة الخشبية؟',
            'type' => 'select',
            'options' => ['خزانة', 'باب', 'مطبخ', 'طاولة', 'أخرى'],
        ],
        [
            'question' => 'هل لديك تصميم جاهز؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'أبعاد القطعة (سم)',
            'type' => 'text',
        ],
        [
            'question' => 'هل الخشب متوفر؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'عدد القطع المطلوبة',
            'type' => 'number',
        ],
        [
            'question' => 'مكان العمل',
            'type' => 'select',
            'options' => ['داخل المنزل', 'خارج المنزل'],
        ],
        [
            'question' => 'ملاحظات إضافية',
            'type' => 'text',
            'is_required' => false,
        ],
        [
            'question' => 'أرفق صورة للتصميم أو القطعة',
            'type' => 'image',
            'is_required' => false,
        ],

    ];

    foreach ($questions as $q) {
        ServiceQuestion::create([
            'category_id' => $id,
            'question' => $q['question'],
            'type' => $q['type'],
            'options' => $q['options'] ?? null,
            'is_required' => $q['is_required'] ?? true,
        ]);
    }
}
private function questionsCarWash($id)
{
    $questions = [

        [
            'question' => 'ما نوع السيارة؟',
            'type' => 'select',
            'options' => ['سيدان', 'SUV', 'شاحنة', 'دراجة'],
        ],
        [
            'question' => 'نوع الخدمة المطلوبة',
            'type' => 'multi_select',
            'options' => ['غسيل خارجي', 'غسيل داخلي', 'تلميع', 'تنظيف محرك'],
        ],
        [
            'question' => 'مدى اتساخ السيارة',
            'type' => 'select',
            'options' => ['خفيف', 'متوسط', 'شديد'],
        ],
        [
            'question' => 'هل تحتاج تنظيف المقاعد؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'هل الخدمة متنقلة؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'موقع السيارة',
            'type' => 'text',
        ],
        [
            'question' => 'ملاحظات إضافية',
            'type' => 'text',
            'is_required' => false,
        ],
        [
            'question' => 'أرفق صورة للسيارة',
            'type' => 'image',
            'is_required' => false,
        ],

    ];

    foreach ($questions as $q) {
        ServiceQuestion::create([
            'category_id' => $id,
            'question' => $q['question'],
            'type' => $q['type'],
            'options' => $q['options'] ?? null,
            'is_required' => $q['is_required'] ?? true,
        ]);
    }
}
private function questionsScreen($id)
{
    $questions = [

        [
            'question' => 'ما نوع الشاشة؟',
            'type' => 'select',
            'options' => ['LED', 'LCD', 'Smart TV'],
        ],
        [
            'question' => 'حجم الشاشة (بالبوصة)',
            'type' => 'number',
        ],
        [
            'question' => 'نوع التركيب',
            'type' => 'select',
            'options' => ['حائط', 'طاولة'],
        ],
        [
            'question' => 'هل الحامل متوفر؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'هل تحتاج تمديد كابلات؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'نوع الجدار',
            'type' => 'select',
            'options' => ['باطون', 'جبصين', 'خشب'],
        ],
        [
            'question' => 'هل المكان مجهز بالكهرباء؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'هل تحتاج ضبط القنوات؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'ملاحظات إضافية',
            'type' => 'text',
            'is_required' => false,
        ],
        [
            'question' => 'أرفق صورة لمكان التركيب',
            'type' => 'image',
            'is_required' => false,
        ],

    ];

    foreach ($questions as $q) {
        ServiceQuestion::create([
            'category_id' => $id,
            'question' => $q['question'],
            'type' => $q['type'],
            'options' => $q['options'] ?? null,
            'is_required' => $q['is_required'] ?? true,
        ]);
    }
}
private function questionsCamera($id)
{
    $questions = [

        [
            'question' => 'كم عدد الكاميرات المطلوبة؟',
            'type' => 'number',
        ],
        [
            'question' => 'نوع الكاميرات',
            'type' => 'select',
            'options' => ['داخلية', 'خارجية', 'كلاهما'],
        ],
        [
            'question' => 'نوع المكان',
            'type' => 'select',
            'options' => ['منزل', 'شركة', 'محل'],
        ],
        [
            'question' => 'هل يوجد نظام مراقبة سابق؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'المميزات المطلوبة',
            'type' => 'multi_select',
            'options' => ['تسجيل فيديو', 'مشاهدة عبر الهاتف', 'كشف حركة', 'رؤية ليلية'],
        ],
        [
            'question' => 'هل الكاميرات متوفرة؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'المساحة التقريبية للمكان (متر)',
            'type' => 'number',
        ],
        [
            'question' => 'ملاحظات إضافية',
            'type' => 'text',
            'is_required' => false,
        ],
        [
            'question' => 'أرفق صورة للمكان',
            'type' => 'image',
            'is_required' => false,
        ],

    ];

    foreach ($questions as $q) {
        ServiceQuestion::create([
            'category_id' => $id,
            'question' => $q['question'],
            'type' => $q['type'],
            'options' => $q['options'] ?? null,
            'is_required' => $q['is_required'] ?? true,
        ]);
    }
}
private function questionsProgramming($id)
{
    $questions = [

        [
            'question' => 'ما نوع المشروع؟',
            'type' => 'select',
            'options' => ['موقع', 'تطبيق موبايل', 'نظام إدارة', 'أخرى'],
        ],
        [
            'question' => 'نوع الموقع',
            'type' => 'select',
            'options' => ['تعريفي', 'متجر إلكتروني', 'منصة خدمات'],
        ],
        [
            'question' => 'عدد الصفحات المتوقع',
            'type' => 'number',
        ],
        [
            'question' => 'المميزات المطلوبة',
            'type' => 'multi_select',
            'options' => ['لوحة تحكم', 'بوابة دفع', 'تسجيل مستخدمين', 'API'],
        ],
        [
            'question' => 'هل لديك تصميم جاهز؟',
            'type' => 'select',
            'options' => ['نعم', 'لا'],
        ],
        [
            'question' => 'ما التقنيات المفضلة؟',
            'type' => 'multi_select',
            'options' => ['Laravel', 'React', 'Flutter', 'Node.js'],
        ],
        [
            'question' => 'موعد التسليم المطلوب',
            'type' => 'text',
        ],
        [
            'question' => 'تفاصيل المشروع',
            'type' => 'text',
        ],
        [
            'question' => 'أرفق ملفات أو تصميم',
            'type' => 'image',
            'is_required' => false,
        ],

    ];

    foreach ($questions as $q) {
        ServiceQuestion::create([
            'category_id' => $id,
            'question' => $q['question'],
            'type' => $q['type'],
            'options' => $q['options'] ?? null,
            'is_required' => $q['is_required'] ?? true,
        ]);
    }
}
private function questionsTutor($id)
{
    $questions = [

        [
            'question' => 'ما المادة المطلوبة؟',
            'type' => 'select',
            'options' => ['رياضيات', 'فيزياء', 'كيمياء', 'لغة عربية', 'لغة إنكليزية'],
        ],
        [
            'question' => 'الصف الدراسي',
            'type' => 'select',
            'options' => ['ابتدائي', 'إعدادي', 'ثانوي', 'جامعي'],
        ],
        [
            'question' => 'نوع التدريس',
            'type' => 'select',
            'options' => ['حضوري', 'أونلاين'],
        ],
        [
            'question' => 'عدد الساعات أسبوعياً',
            'type' => 'number',
        ],
        [
            'question' => 'مستوى الطالب',
            'type' => 'select',
            'options' => ['ضعيف', 'متوسط', 'جيد'],
        ],
        [
            'question' => 'الهدف من الدروس',
            'type' => 'multi_select',
            'options' => ['تحسين المستوى', 'النجاح في اختبار', 'تحضير شهادة'],
        ],
        [
            'question' => 'موقع الطالب',
            'type' => 'text',
        ],
        [
            'question' => 'ملاحظات إضافية',
            'type' => 'text',
            'is_required' => false,
        ],

    ];

    foreach ($questions as $q) {
        ServiceQuestion::create([
            'category_id' => $id,
            'question' => $q['question'],
            'type' => $q['type'],
            'options' => $q['options'] ?? null,
            'is_required' => $q['is_required'] ?? true,
        ]);
    }
}
private function questionsTranslation($id)
{
    $questions = [

        [
            'question' => 'اللغة الأصلية',
            'type' => 'select',
            'options' => ['عربي', 'إنكليزي', 'فرنسي', 'ألماني'],
        ],
        [
            'question' => 'اللغة المطلوبة',
            'type' => 'select',
            'options' => ['عربي', 'إنكليزي', 'فرنسي', 'ألماني'],
        ],
        [
            'question' => 'نوع النص',
            'type' => 'select',
            'options' => ['عام', 'تقني', 'قانوني', 'طبي'],
        ],
        [
            'question' => 'عدد الكلمات',
            'type' => 'number',
        ],
        [
            'question' => 'نوع الخدمة',
            'type' => 'multi_select',
            'options' => ['ترجمة فقط', 'ترجمة + تدقيق', 'ترجمة احترافية'],
        ],
        [
            'question' => 'موعد التسليم',
            'type' => 'text',
        ],
        [
            'question' => 'ملاحظات إضافية',
            'type' => 'text',
            'is_required' => false,
        ],
        [
            'question' => 'أرفق الملف',
            'type' => 'image', // أو file مستقبلاً
            'is_required' => false,
        ],

    ];

    foreach ($questions as $q) {
        ServiceQuestion::create([
            'category_id' => $id,
            'question' => $q['question'],
            'type' => $q['type'],
            'options' => $q['options'] ?? null,
            'is_required' => $q['is_required'] ?? true,
        ]);
    }
}
private function questionsNurse($id)
{
    ServiceQuestion::insert([

        [
            'category_id'=>$id,
            'question'=>'ما نوع الخدمة الطبية المطلوبة؟',
            'type'=>'select',
            'options'=>json_encode(['حقن', 'تضميد جروح', 'رعاية مسن', 'قياس ضغط وسكر']),
            'is_required'=>true
        ],

        [
            'category_id'=>$id,
            'question'=>'هل المريض رجل أم امرأة؟',
            'type'=>'select',
            'options'=>json_encode(['رجل', 'امرأة']),
            'is_required'=>true
        ],

        [
            'category_id'=>$id,
            'question'=>'ما عمر المريض؟',
            'type'=>'number',
            'options'=>null,
            'is_required'=>true
        ],

        [
            'category_id'=>$id,
            'question'=>'هل المريض قادر على الحركة؟',
            'type'=>'select',
            'options'=>json_encode(['نعم', 'بصعوبة', 'لا']),
            'is_required'=>true
        ],

        [
            'category_id'=>$id,
            'question'=>'كم عدد الساعات المطلوبة يومياً؟',
            'type'=>'number',
            'options'=>null,
            'is_required'=>true
        ],

        [
            'category_id'=>$id,
            'question'=>'هل يوجد تقارير طبية؟',
            'type'=>'image',
            'options'=>null,
            'is_required'=>false
        ],

        [
            'category_id'=>$id,
            'question'=>'حدد موقع المريض',
            'type'=>'text',
            'options'=>null,
            'is_required'=>true
        ]

    ]);
}
private function questionsBarber($id)
{
    ServiceQuestion::insert([

        [
            'category_id'=>$id,
            'question'=>'ما نوع الحلاقة المطلوبة؟',
            'type'=>'select',
            'options'=>json_encode(['عادي', 'Fade', 'Skin Fade', 'تصفيف']),
            'is_required'=>true
        ],

        [
            'category_id'=>$id,
            'question'=>'هل تريد حلاقة شعر فقط أم شعر ولحية؟',
            'type'=>'select',
            'options'=>json_encode(['شعر فقط', 'شعر + لحية']),
            'is_required'=>true
        ],

        [
            'category_id'=>$id,
            'question'=>'هل الخدمة في المنزل أم في الصالون؟',
            'type'=>'select',
            'options'=>json_encode(['المنزل', 'الصالون']),
            'is_required'=>true
        ],

        [
            'category_id'=>$id,
            'question'=>'هل لديك صورة للستايل المطلوب؟',
            'type'=>'image',
            'options'=>null,
            'is_required'=>false
        ],

        [
            'category_id'=>$id,
            'question'=>'ما موقع الخدمة؟',
            'type'=>'text',
            'options'=>null,
            'is_required'=>true
        ]

    ]);
}
private function questionsMakeup($id)
{
    ServiceQuestion::insert([

        [
            'category_id'=>$id,
            'question'=>'ما نوع المناسبة؟',
            'type'=>'select',
            'options'=>json_encode(['زفاف', 'خطوبة', 'حفلة', 'جلسة تصوير']),
            'is_required'=>true
        ],

        [
            'category_id'=>$id,
            'question'=>'هل المكياج للعروس؟',
            'type'=>'select',
            'options'=>json_encode(['نعم', 'لا']),
            'is_required'=>true
        ],

        [
            'category_id'=>$id,
            'question'=>'كم عدد الأشخاص؟',
            'type'=>'number',
            'options'=>null,
            'is_required'=>true
        ],

        [
            'category_id'=>$id,
            'question'=>'هل تحتاج تسريحة شعر؟',
            'type'=>'select',
            'options'=>json_encode(['نعم', 'لا']),
            'is_required'=>true
        ],

        [
            'category_id'=>$id,
            'question'=>'أرفق صورة للمكياج المطلوب',
            'type'=>'image',
            'options'=>null,
            'is_required'=>false
        ],

        [
            'category_id'=>$id,
            'question'=>'ما موقع الخدمة؟',
            'type'=>'text',
            'options'=>null,
            'is_required'=>true
        ],

        [
            'category_id'=>$id,
            'question'=>'ما موعد المناسبة؟',
            'type'=>'text',
            'options'=>null,
            'is_required'=>true
        ]

    ]);
}
private function questionsPhotography($id)
{
    $questions = [

        ['q'=>'ما نوع المناسبة؟','type'=>'select','options'=>['زفاف','حفلة','تخرج','أخرى']],
        ['q'=>'كم عدد ساعات التصوير؟','type'=>'number'],
        ['q'=>'هل تحتاج تصوير فيديو أيضاً؟','type'=>'select','options'=>['نعم','لا']],
        ['q'=>'هل تحتاج تعديل الصور؟','type'=>'select','options'=>['نعم','لا']],
        ['q'=>'ما موقع التصوير؟','type'=>'text'],
        ['q'=>'ما تاريخ المناسبة؟','type'=>'text'],

        // 🔥 صورة
        ['q'=>'أرفق مثال للصورة المطلوبة','type'=>'image','required'=>false],

    ];

    foreach($questions as $q){
        ServiceQuestion::create([
            'category_id'=>$id,
            'question'=>$q['q'],
            'type'=>$q['type'],
            'options'=>$q['options'] ?? null,
            'is_required'=>$q['required'] ?? true
        ]);
    }
}
private function questionsEvent($id)
{
    $questions = [

        ['q'=>'ما نوع الحفل؟','type'=>'select','options'=>['زفاف','عيد ميلاد','مؤتمر','أخرى']],
        ['q'=>'كم عدد الضيوف المتوقع؟','type'=>'number'],
        ['q'=>'هل تحتاج ديكور؟','type'=>'select','options'=>['نعم','لا']],
        ['q'=>'هل تحتاج طعام؟','type'=>'select','options'=>['نعم','لا']],
        ['q'=>'هل تحتاج موسيقى؟','type'=>'select','options'=>['نعم','لا']],
        ['q'=>'ما موقع الحفل؟','type'=>'text'],
        ['q'=>'ما تاريخ الحفل؟','type'=>'text'],

        // 🔥 صورة
        ['q'=>'أرفق صورة للديكور المطلوب','type'=>'image','required'=>false],

    ];

    foreach($questions as $q){
        ServiceQuestion::create([
            'category_id'=>$id,
            'question'=>$q['q'],
            'type'=>$q['type'],
            'options'=>$q['options'] ?? null,
            'is_required'=>$q['required'] ?? true
        ]);
    }
}
private function questionsDelivery($id)
{
    $questions = [

        ['q'=>'ما نوع الطلب؟','type'=>'text'],
        ['q'=>'من أين سيتم الاستلام؟','type'=>'text'],
        ['q'=>'إلى أين سيتم التوصيل؟','type'=>'text'],
        ['q'=>'ما وزن الطلب؟','type'=>'number'],
        ['q'=>'هل الطلب قابل للكسر؟','type'=>'select','options'=>['نعم','لا']],
        ['q'=>'هل الدفع عند الاستلام؟','type'=>'select','options'=>['نعم','لا']],
        ['q'=>'ما الوقت المطلوب للتوصيل؟','type'=>'text'],

        // 🔥 صورة
        ['q'=>'أرفق صورة للطلب','type'=>'image','required'=>false],

    ];

    foreach($questions as $q){
        ServiceQuestion::create([
            'category_id'=>$id,
            'question'=>$q['q'],
            'type'=>$q['type'],
            'options'=>$q['options'] ?? null,
            'is_required'=>$q['required'] ?? true
        ]);
    }
}
private function questionsMoving($id)
{
    $questions = [

        ['q'=>'ما نوع الأثاث؟','type'=>'multi_select','options'=>['غرف نوم','صالون','مطبخ','مكتب']],
        ['q'=>'كم عدد الغرف؟','type'=>'number'],
        ['q'=>'هل يوجد طابق مرتفع؟','type'=>'select','options'=>['نعم','لا']],
        ['q'=>'هل يوجد مصعد؟','type'=>'select','options'=>['نعم','لا']],
        ['q'=>'هل تحتاج فك وتركيب الأثاث؟','type'=>'select','options'=>['نعم','لا']],
        ['q'=>'من أين سيتم النقل؟','type'=>'text'],
        ['q'=>'إلى أين سيتم النقل؟','type'=>'text'],

        // 🔥 صورة
        ['q'=>'أرفق صور للأثاث','type'=>'image','required'=>false],

    ];

    foreach($questions as $q){
        ServiceQuestion::create([
            'category_id'=>$id,
            'question'=>$q['q'],
            'type'=>$q['type'],
            'options'=>$q['options'] ?? null,
            'is_required'=>$q['required'] ?? true
        ]);
    }
}

private function questionsGuard($id)
{
    $questions = [

        ['q'=>'ما نوع المكان؟','type'=>'select','options'=>['منزل','شركة','مستودع','أخرى']],
        ['q'=>'كم عدد الحراس المطلوبين؟','type'=>'number'],
        ['q'=>'هل الحراسة ليلاً أم نهاراً؟','type'=>'select','options'=>['ليلاً','نهاراً','كلاهما']],
        ['q'=>'ما مدة الحراسة المطلوبة (بالأيام)؟','type'=>'number'],
        ['q'=>'ما موقع المكان؟','type'=>'text'],

        // 🔥 صورة
        ['q'=>'أرفق صورة للمكان','type'=>'image','required'=>false],

    ];

    foreach($questions as $q){
        ServiceQuestion::create([
            'category_id'=>$id,
            'question'=>$q['q'],
            'type'=>$q['type'],
            'options'=>$q['options'] ?? null,
            'is_required'=>$q['required'] ?? true
        ]);
    }
}
private function questionsPet($id)
{
    $questions = [

        ['q'=>'ما نوع الحيوان؟','type'=>'select','options'=>['كلب','قط','طائر','أخرى']],
        ['q'=>'كم عمر الحيوان؟','type'=>'number'],
        ['q'=>'ما نوع الرعاية المطلوبة؟','type'=>'multi_select','options'=>['إطعام','تنظيف','تمشية','علاج']],
        ['q'=>'هل يحتاج الحيوان أدوية؟','type'=>'select','options'=>['نعم','لا']],
        ['q'=>'كم مدة الرعاية؟','type'=>'number'],
        ['q'=>'ما موقع الخدمة؟','type'=>'text'],

        // 🔥 صورة
        ['q'=>'أرفق صورة للحيوان','type'=>'image','required'=>false],

    ];

    foreach($questions as $q){
        ServiceQuestion::create([
            'category_id'=>$id,
            'question'=>$q['q'],
            'type'=>$q['type'],
            'options'=>$q['options'] ?? null,
            'is_required'=>$q['required'] ?? true
        ]);
    }
}

private function questionsTailor($id)
{
    $questions = [

        ['q'=>'ما نوع الملابس؟','type'=>'select','options'=>['رجالي','نسائي','أطفال']],
        ['q'=>'هل العمل تفصيل أم تعديل؟','type'=>'select','options'=>['تفصيل','تعديل']],
        ['q'=>'ما المقاسات المطلوبة؟','type'=>'text'],
        ['q'=>'هل القماش متوفر؟','type'=>'select','options'=>['نعم','لا']],
        ['q'=>'كم عدد القطع؟','type'=>'number'],
        ['q'=>'ما موعد التسليم المطلوب؟','type'=>'text'],

        // 🔥 صورة
        ['q'=>'أرفق صورة للتصميم المطلوب','type'=>'image','required'=>false],

    ];

    foreach($questions as $q){
        ServiceQuestion::create([
            'category_id'=>$id,
            'question'=>$q['q'],
            'type'=>$q['type'],
            'options'=>$q['options'] ?? null,
            'is_required'=>$q['required'] ?? true
        ]);
    }
}
private function questionsAppliance($id)
{
    $questions = [

        ['q'=>'ما نوع الجهاز؟','type'=>'select','options'=>['غسالة','ثلاجة','مكيف','فرن','أخرى']],
        ['q'=>'ما الماركة؟','type'=>'text'],
        ['q'=>'ما موديل الجهاز؟','type'=>'text'],
        ['q'=>'ما المشكلة التي تواجهها؟','type'=>'text'],
        ['q'=>'هل الجهاز يعمل؟','type'=>'select','options'=>['يعمل جزئياً','لا يعمل']],
        ['q'=>'هل يظهر رمز خطأ على الشاشة؟','type'=>'select','options'=>['نعم','لا']],
        ['q'=>'متى بدأت المشكلة؟','type'=>'text'],
        ['q'=>'هل تم إصلاح الجهاز سابقاً؟','type'=>'select','options'=>['نعم','لا']],
        ['q'=>'هل الجهاز داخل الضمان؟','type'=>'select','options'=>['نعم','لا']],

        // 🔥 أهم شيء هنا
        ['q'=>'أرفق صورة أو فيديو للمشكلة','type'=>'image','required'=>false],

    ];

    foreach($questions as $q){
        ServiceQuestion::create([
            'category_id'=>$id,
            'question'=>$q['q'],
            'type'=>$q['type'],
            'options'=>$q['options'] ?? null,
            'is_required'=>$q['required'] ?? true
        ]);
    }
}
}


